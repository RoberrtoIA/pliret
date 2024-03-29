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

    public function enrollments()
    {
        return $this->belongsToMany(User::class, 'enrollments')
            ->as('enrollment')
            ->withPivot('score', 'active', 'created_at')
            ->using(Enrollment::class);
    }

    public function trainers()
    {
        return $this->belongsToMany(User::class, 'trainers')
            ->as('trainer')
            ->withPivot('active', 'created_at')
            ->using(Trainer::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}
