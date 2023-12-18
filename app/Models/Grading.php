<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grading extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $casts = ['snapshot' => 'array'];

    protected $fillable = ['gradable_id', 'gradable_type', 'comments', 'grade'];

    public function gradable()
    {
        return $this->morphTo();
    }

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }
}
