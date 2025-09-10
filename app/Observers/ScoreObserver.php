<?php

namespace App\Observers;

use App\Models\Score;
use App\Traits\ActivityLogger;

class ScoreObserver
{
    use ActivityLogger;
    /**
     * Handle the Score "created" event.
     */
    public function created(Score $score): void
    {
        $this->logCreated($score, " for {$score->subject->name} in {$score->class->name} for {$score->term->name}");
    }

    /**
     * Handle the Score "updated" event.
     */
    public function updated(Score $score): void
    {
        $this->logUpdated($score, " for {$score->subject->name} in {$score->class->name} for {$score->term->name}");
    }

    /**
     * Handle the Score "deleted" event.
     */
    public function deleted(Score $score): void
    {
        $this->logDeleted($score, " for {$score->subject->name} in {$score->class->name} for {$score->term->name}");
    }

    /**
     * Handle the Score "restored" event.
     */
    public function restored(Score $score): void
    {
        $this->logRestored($score, " for {$score->subject->name} in {$score->class->name} for {$score->term->name}");
    }

    /**
     * Handle the Score "force deleted" event.
     */
    public function forceDeleted(Score $score): void
    {
        $this->logDeleted($score, " for {$score->subject->name} in {$score->class->name} for {$score->term->name}");
    }
}
