<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Enrollment extends Pivot
{
    protected $table = 'enrollments';

    public $incrementing = true;
}
