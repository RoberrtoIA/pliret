<?php

namespace App\Listeners;

use App\Events\InterviewFinished;
use App\Services\InterviewService;
use Illuminate\Support\Facades\App;

// use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Queue\InteractsWithQueue;

class UpdateInterviewGrade
{
    public function handle(InterviewFinished $event)
    {
        $service = App::make(InterviewService::class);
        $service->updateGrade($event->assignment);
    }
}
