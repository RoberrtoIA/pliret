<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Developer extends Pivot
{
    protected $table = 'developers';

    public $incrementing = true;
}
