<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification implements ShouldQueue

{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected $token)
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }


    public function toMail(object $notifiable): MailMessage
    {
        $url = url(config('app.url') . route('password.reset', [
                'token' => $this->token,
                'email' => $notifiable->getEmailForPasswordReset(),
                'user' => strtolower(str(class_basename($notifiable))->replace('_', '')),
            ], false));




        return (new MailMessage)
            ->from('support@velstack.com', config('app.name'))
            ->replyTo('support@velstack.com')
            ->subject(config('app.name').' Request to Reset Password  - '.now()->toDateTimeLocalString().'(GMT)')
            ->view('notifications.password-reset', ['url' => $url, 'name' => $notifiable->fullname ?? $notifiable->name]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
