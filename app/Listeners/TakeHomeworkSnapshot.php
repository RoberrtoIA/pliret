<?php

namespace App\Listeners;

use App\Events\HomeworkStarted;
use App\Services\HomeworkService;
use Illuminate\Support\Facades\App;

// use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Queue\InteractsWithQueue;

class TakeHomeworkSnapshot
{
    public function handle(HomeworkStarted $event)
    {
        $service = App::make(HomeworkService::class);
        $service->takeSnapshot($event->assignment);
    }
}
