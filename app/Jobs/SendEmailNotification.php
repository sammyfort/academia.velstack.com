<?php

namespace App\Jobs;

use App\Notifications\EmailNotifier;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendEmailNotification implements ShouldQueue
{
    use Queueable;


    public function __construct(protected $notifiable, protected string $subject, protected string $view, protected array $data)
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->notifiable->notify(new EmailNotifier($this->subject, $this->view, $this->data));
    }
}
