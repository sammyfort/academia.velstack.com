<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class SMS
{

    public static function send(string $message, string $recipients, string $sender, string $token, $title = null)
    {
        $data = [
            'title' => $title ?? config('app.name'). " Notifications",
            'sender' => "$sender",
            'recipient' => "$recipients",
            'message' => $message,
            'is_scheduled' => false
        ];

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer $token"
            ])
                ->timeout(30)
                ->post('https://sms.velstack.com/api/quick/sms', $data);
            $response->throw();
            return $response;

        } catch (Exception $exception) {
          info($exception);
        }
        return null;
    }

}
