<?php

namespace App\Listeners;

use App\Events\HomeworkStarted;
use App\Services\HomeworkService;
// use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Queue\InteractsWithQueue;

class TakeHomeworkSnapshot
{
    public function handle(HomeworkStarted $event, HomeworkService $service)
    {
        $service->takeSnapshot($event->assignment);
    }
}
