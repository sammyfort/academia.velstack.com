<?php

namespace App\Jobs;

use App\Models\School;
use App\Services\SMS;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SMSSenderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 500;
    public int $retries = 2;

    /**
     * Create a new job instance.
     */
    public function __construct(protected string $message,
                                protected string $recipients,
                                protected string $sender = '',
                                protected string $token = '',
                                protected string $title = '',
                                protected ?School $school = null)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->school){
            SMS::send($this->message, $this->recipients, $this->sender, $this->token, $this->title);
        }
    }
}
