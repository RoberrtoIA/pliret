<?php

namespace App\Listeners;

use App\Events\HomeworkFinished;
use App\Services\HomeworkService;
// use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Queue\InteractsWithQueue;

class UpdateHomeworkGrade
{
    public function handle(HomeworkFinished $event, HomeworkService $service)
    {
        $service->updateGrade($event->assignment);
    }
}
