<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Trainer extends Pivot
{
    protected $table = 'trainers';

    public $incrementing = true;
}
