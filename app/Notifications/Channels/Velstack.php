<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Velstack
{
    private string $quickSMSURL = 'https://sms.velstack.com/api/quick/sms';

    private string $apiKey;
    private string $sender;
    private string $message;

    public function __construct()
    {
        $this->apiKey = config('services.velstack.API_KEY');
        $this->sender = config('services.velstack.SENDER_ID');
    }

    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toVelstack();
        $receiverMobile = $notifiable->routeNotificationFor('velstack') ?? $notifiable->tel;
        $this->sendQuickSMS($receiverMobile, $message);
    }

    public function sendQuickSMS(array|string $recipients, $message=null): void{
        if (!is_array($recipients)){
            $recipients = [$recipients];
        }
        $data = [
            'title' => 'Alexoa',
            'recipient' => $recipients,
            'sender' => $this->sender,
            'message' => $message ?? $this->message,
            'is_scheduled' => false,
        ];
        $response = Http::withHeaders([
            'Authorization' => "Bearer $this->apiKey",
            'Accept' => 'application/json',
        ])->post($this->quickSMSURL, $data);

        if ($response->clientError()){
            Log::error(" Velstack Error; error in request: " . $response->toException()->getMessage());
        }
        elseif ($response->serverError()){
            Log::critical('Sending to Velstack failed; Velstack server encountered an error: ' . $response->toException()->getMessage());
        }
        else {
            Log::critical('Sending to Velstack failed; unexpected error occurred: ' . $response->toException()->getMessage());
        }
    }
}
