<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Assignment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'execution_id', 'module_id', 'user_id',
    ];

    protected $casts = [
        'homework_snapshot' => 'array',
        'interview_snapshot' => 'array',
    ];

    public function gradings() {
        return $this->hasMany(Grading::class);
    }

    public function homeworkGradings() {
        return $this->hasMany(Grading::class)
            ->where('gradable_type', EvaluationCriteria::class);
    }

    public function interviewGradings() {
        return $this->hasMany(Grading::class)
            ->where('gradable_type', Question::class);
    }

    public function module() {
        return $this->belongsTo(Module::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function execution() {
        return $this->belongsTo(Execution::class);
    }
}
