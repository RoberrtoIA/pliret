<?php

namespace App\Listeners;

use App\Events\InterviewStarted;
use App\Services\InterviewService;
use Illuminate\Support\Facades\App;

// use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Queue\InteractsWithQueue;

class TakeInterviewSnapshot
{
    public function handle(InterviewStarted $event)
    {
        $service = App::make(InterviewService::class);
        $service->takeSnapshot($event->assignment);
    }
}
