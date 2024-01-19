<?php

namespace App\Listeners;

use App\Events\HomeworkFinished;
use App\Services\HomeworkService;
use Illuminate\Support\Facades\App;

// use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Queue\InteractsWithQueue;

class UpdateHomeworkGrade
{
    public function handle(HomeworkFinished $event)
    {
        $service = App::make(HomeworkService::class);
        $service->updateGrade($event->assignment);
    }
}
