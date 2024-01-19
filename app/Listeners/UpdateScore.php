<?php

namespace App\Listeners;

use App\Events\GradeUpdated;
use App\Services\UserService;
use Illuminate\Support\Facades\App;

// use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Queue\InteractsWithQueue;

class UpdateScore
{
    public function handle(GradeUpdated $event)
    {
        $service = App::make(UserService::class);
        $service->updateScore($event->assignment);
    }
}
