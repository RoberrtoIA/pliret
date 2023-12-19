<?php

namespace App\Listeners;

use App\Events\InterviewFinished;
use App\Services\InterviewService;
// use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Queue\InteractsWithQueue;

class UpdateInterviewGrade
{
    public function handle(InterviewFinished $event, InterviewService $service)
    {
        $service->updateGrade($event->assignment);
    }
}
