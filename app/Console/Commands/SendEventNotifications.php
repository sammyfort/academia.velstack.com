<?php

namespace App\Console\Commands;

use App\Enum\NotifyClass;
use App\Jobs\SMSSenderJob;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class SendEventNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'event:notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $today = Carbon::today();
        $events = Event::query()->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->where('send_notification_to', '!=', NotifyClass::NONE->value)
            ->get();

        if ($events->isEmpty()) {
            $this->info('No events to send notifications for today.');
            return;
        }


        foreach ($events as $event) {

            if ($event->school->readyForSMS() && $event->school->communication->send_upcoming_events){
                if (!$event->interval || $event->interval < 1) {
                    $this->warn("Event ID $event->id has an invalid notification interval. Skipping...");
                    continue;
                }

                $notificationDates = $this->calculateNotificationDates(
                    Carbon::parse($event->start_date),
                    Carbon::parse($event->end_date),
                    $event->interval
                );
                if ($notificationDates->contains($today)) {
                    dispatch(new SMSSenderJob($event->school, $event->message,  $event->recipients()));
                    $this->info("sending message to {$event->recipients()}");
                }
            }else{
                $this->info('school is not ready for notifications.');
            }

        }
    }
    /**
     * Calculate notification dates between a given range based on the interval.
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @param int $interval
     * @return  Collection
     */
        private function calculateNotificationDates(Carbon $startDate, Carbon $endDate, int $interval): Collection
        {
        $dates = collect();
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDays($interval)) {
            $dates->push($date->copy());
        }

        return $dates;
    }
}
