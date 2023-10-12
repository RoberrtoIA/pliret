<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Execution extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['program_id', 'start_date', 'end_date'];

    protected $casts = [
        'program_execution_content' => 'array',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
