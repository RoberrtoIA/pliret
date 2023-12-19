<?php

namespace App\Events;

use App\Models\Assignment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class HomeworkFinished
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public readonly Assignment $assignment)
    {
    }
}
