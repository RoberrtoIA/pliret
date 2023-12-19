<?php

namespace App\Listeners;

use App\Events\GradeUpdated;
use App\Services\UserService;
// use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Queue\InteractsWithQueue;

class UpdateScore
{
    public function handle(GradeUpdated $event, UserService $service)
    {
        $service->updateScore($event->assignment);
    }
}
