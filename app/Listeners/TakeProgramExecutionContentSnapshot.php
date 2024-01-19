<?php

namespace App\Listeners;

use App\Events\ExecutionFinished;
use App\Services\ExecutionService;
use Illuminate\Support\Facades\App;

// use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Queue\InteractsWithQueue;

class TakeProgramExecutionContentSnapshot
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\ExecutionFinished  $event
     * @return void
     */
    public function handle(ExecutionFinished $event)
    {
        $service = App::make(ExecutionService::class);
        $service->takeProgramSnapshot($event->execution);
    }
}
