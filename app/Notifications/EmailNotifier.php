<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailNotifier extends Notification implements ShouldQueue
{
    use Queueable;


    public function __construct(protected string $subject, protected string $view, protected array $data)
    {
        //
    }


    public function via(object $notifiable): array
    {
        return ['mail'];
    }


    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->from('support@velstack.com', config('app.name'))
            ->replyTo('support@velstack.com')
            ->subject($this->subject)
            ->view("notifications.$this->view", $this->data);
    }


    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
