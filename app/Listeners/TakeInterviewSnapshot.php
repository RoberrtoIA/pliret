<?php

namespace App\Listeners;

use App\Events\InterviewStarted;
use App\Services\InterviewService;
// use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Queue\InteractsWithQueue;

class TakeInterviewSnapshot
{
    public function handle(InterviewStarted $event, InterviewService $service)
    {
        $service->takeSnapshot($event->assignment);
    }
}
