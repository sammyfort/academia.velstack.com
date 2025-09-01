<?php

namespace App\Http\Controllers;

use App\Enums\PaymentPlatform;
use App\Enums\PaymentStatus;
use App\Enums\Promotable;
use App\Http\Requests\PromotionRequest;
use App\Models\Promotion;
use App\Models\PromotionPlan;
use App\Notifications\PromotionSuccessNotification;
use App\Services\HubtelService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use \Illuminate\Http\RedirectResponse;

class PromotionController extends Controller
{
    private HubtelService $hubtelService;

    public function __construct()
    {
        $this->hubtelService = new HubtelService();
    }

    public function payUsingPoints(PromotionRequest $request)
    {
        $validatedData = $request->validated();
        $user = auth()->user();
        $plan = PromotionPlan::query()->find($validatedData['plan_id']);

        if ($plan->price > $user->points_in_cedis){
            return back()->with(errorRes());
        }

        $reference = Str::uuid()->toString();
        $promotableEnum = Promotable::from($validatedData['promotable_type']);
        $promotableModel = Promotable::getModel($promotableEnum);
        $promotable = $promotableModel::query()->find($validatedData['promotable_id']);

        if (! $promotable) {
            return back()->with(errorRes());
        }

        DB::beginTransaction();
        try {
            $now = now();

            // Check for overlapping active promotions
            $activePromotions = $promotable->promotions()
                ->running()
                ->get();

            $arrearsDays = 0;
            if ($activePromotions->count()) {
                $latest = $activePromotions->first();
                $arrearsDays = max((int) $now->diffInDays(Carbon::parse($latest->ends_at)), 0);
                foreach ($activePromotions as $old) {
                    $old->update(['ends_at' => $now]);
                }
            }

            $totalDays = $plan->number_of_days + $arrearsDays;
            $endsAt = $now->copy()->addDays($totalDays);

            $promotable->promotions()->create([
                'plan_id' => $plan->id,
                'payment_reference' => $reference,
                'payment_status' => PaymentStatus::PAID,
                'amount' => $plan->price,
                'payment_platform' => PaymentPlatform::SYSTEM_POINTS,
                'starts_at'       => $now,
                'ends_at'         => $endsAt,
            ]);

            $newUserPoints = $user->points - ( $plan->price / config('app.point_cedi_rate'));

            // charge user's points
            $user->update([
                'points' => $newUserPoints
            ]);

            DB::commit();

            $promotableName = $promotable->name ?? $promotable->title;
            $user->notify(new PromotionSuccessNotification($promotableName, $plan));

            return redirect()->to(
                url()->previous() . (str_contains(url()->previous(), '?') ? '&' : '?') . "reference=$reference&payment_status=success"
            );
        }
        catch (\Exception $exception){
            DB::rollBack();
            return back()->with(errorRes());
        }
    }

    public function initializeHubtel(PromotionRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();
        $user = auth()->user();
        $plan = PromotionPlan::query()->find($validatedData['plan_id']);
        $reference = Str::uuid()->toString();
        $promotableEnum = Promotable::from($validatedData['promotable_type']);
        $promotableModel = Promotable::getModel($promotableEnum);
        $promotable = $promotableModel::query()->find($validatedData['promotable_id'], ['id', 'slug']);
        if (! $promotable) {
            return back()->with(errorRes());
        }
        $promotableRoute = Promotable::getRoute($promotableEnum, $promotable->slug)."?reference=$reference";
        $redirectUrl = route('promotions.payment.verify');

        $data = [
            "totalAmount" => $plan->price,
            "payeeName" => $user->fullname,
            "payeeMobileNumber" => $user->mobile,
            "payeeEmail" => $user->email,
            "description" => "Alexoa promotion subscription",
            "callbackUrl" => $redirectUrl,
            "returnUrl" => "$promotableRoute&payment_status=pending",
            "merchantAccountNumber" => config('app.hubtel_account_number'),
            "cancellationUrl" => "$promotableRoute&payment_status=cancelled",
            "clientReference" => $reference
        ];

        $payment = $this->hubtelService->initializePayment($data);
        if ($payment && isset($payment['checkoutUrl'])){
            $promotable->promotions()->create([
                'plan_id' => $plan->id,
                'payment_reference' => $reference,
                'checkout_id' => $payment['checkoutId'],
                'payment_status' => PaymentStatus::PENDING,
                'amount' => $plan->price,
                'payment_platform' => PaymentPlatform::HUBTEL,
            ]);
            return back()->with(successRes('Url generated', ['authorization_url' => $payment['checkoutUrl']]));
        }
        return back()->with(errorRes());
    }

    public function verifyHubtel(Request $request): Response
    {
        Log::info('Hubtel Webhook Received', $request->all());
        Log::info('IP Address: ' . $request->ip());

        $allowedIps = [
            '52.50.116.54', '18.202.122.131', '52.31.15.68', '108.129.40.25' // Hubtel IPs
        ];

        if (!in_array($request->ip(), $allowedIps)) {
            Log::warning('Unauthorized IP: ' . $request->ip());
            return response('', 200);
        }

        $payload = $request->all();
        $data = $payload['Data'] ?? [];

        if (($payload['ResponseCode'] ?? '') !== '0000') {
            Log::warning('Invalid Hubtel webhook response code', $payload);
            return response('', 200);
        }

        $reference = $data['ClientReference'] ?? null;
        $checkoutId = $data['CheckoutId'] ?? null;
        $status = strtolower($data['Status'] ?? '');
        $amount = (float) ($data['Amount'] ?? 0);
        $channel = $data['PaymentDetails']['Channel'] ?? null;

        if (!$reference || !$status || !$amount) {
            Log::warning('Incomplete Hubtel webhook data', $data);
            return response('', 200);
        }

        $promotion = Promotion::query()
            ->where('payment_status', PaymentStatus::PENDING)
            ->where('payment_reference', $reference)
            ->first();

        if (! $promotion) {
            Log::warning('Promotion not found for reference: ' . $reference);
            return response('', 200);
        }

        $promotable = $promotion->promotable;
        $plan = $promotion->plan;

        if (! $promotable || ! $plan) {
            Log::warning('Missing promotable or plan', ['promotion_id' => $promotion->id]);
            return response('', 200);
        }

        $now = now();

        if ($status === 'success' && $amount >= $plan->price) {
            // Check for overlapping active promotions
            $activePromotions = $promotable->promotions()
                ->where('id', '!=', $promotion->id)
                ->where('payment_status', PaymentStatus::PAID)
                ->where('ends_at', '>', $now)
                ->latest()
                ->get();

            DB::beginTransaction();
            try {
                $arrearsDays = 0;
                if ($activePromotions->count()) {
                    $latest = $activePromotions->first();
                    $arrearsDays = max((int) $now->diffInDays(Carbon::parse($latest->ends_at)), 0);

                    foreach ($activePromotions as $old) {
                        $old->update(['ends_at' => $now]);
                    }
                }

                $totalDays = $plan->number_of_days + $arrearsDays;
                $endsAt = $now->copy()->addDays($totalDays);

                $promotion->update([
                    'checkout_id'     => $checkoutId,
                    'payment_channel' => $channel,
                    'starts_at'       => $now,
                    'ends_at'         => $endsAt,
                    'payment_status'  => PaymentStatus::PAID,
                ]);

                Log::info('Promotion marked as PAID', ['reference' => $reference]);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Failed to process promotion payment', [
                    'error' => $e->getMessage(),
                    'reference' => $reference
                ]);
            }
        } else {
            $promotion->update([
                'payment_status' => PaymentStatus::FAILED,
            ]);
            Log::info('Payment failed or amount mismatch', ['reference' => $reference]);
        }

        return response('', 200);
    }
}
