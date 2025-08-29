<?php

namespace App\Http\Controllers;

use App\Http\Requests\Signboard\RateRequest;
use App\Models\Product;
use App\Models\Service;
use App\Models\Signboard;
use App\Models\User;
use App\Notifications\NewReviewNotification;
use Illuminate\Support\Facades\DB;

class RatingController extends Controller
{
    public function rate(RateRequest $request): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validated();
        DB::beginTransaction();
        try {
            $reviewData = [
                'review' => $validatedData['review'],
                'ratings' => [
                    'overall' => $validatedData['overall'],
                    'customer_service' => $validatedData['customer_service'],
                    'quality' => $validatedData['quality'],
                    'price' => $validatedData['price'],
                    'communication' => $validatedData['communication'],
                    'speed' => $validatedData['speed'],
                ],
            ];

            $ratable = match ($validatedData['ratable_type']){
                'signboard' => Signboard::query()->find($validatedData['ratable_id']),
                'product' => Product::query()->find($validatedData['ratable_id']),
                'service' => Service::query()->find($validatedData['ratable_id']),
                default => null,
            };

            if ($ratable){
                // check if user has already rated
                $review = $ratable->reviews()->where('user_id', auth()->id())->first();
                if ($review) {
                    $ratable->updateReview($review->id, $reviewData);
                } else {
                    $review = $ratable->addReview($reviewData, auth()->id());
                }
                DB::commit();
                $ratable->refresh();

                // notify owner
                $creator = User::query()->find($ratable->created_by_id);

                if ($creator){
                    $creator->notify(new NewReviewNotification(auth()->user(), $review, ucfirst($validatedData['ratable_type'])));
                }

                $data['ratable'] = $ratable;
                return back()->with(successRes('Your Ratings was recorded successfully', $data));
            }

        return back()->with(errorRes());

        } catch (\Exception $exception) {
            DB::rollBack();
            return back()->with(errorRes());
        }
    }
}
