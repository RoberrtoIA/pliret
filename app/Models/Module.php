<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['title', 'description', 'content', 'homework_content', 'program_id'];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function evaluation_criteria()
    {
        return $this->hasMany(EvaluationCriteria::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}
