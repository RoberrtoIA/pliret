<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['question', 'grade_definitions', 'topic_id'];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}
