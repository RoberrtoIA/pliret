<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EvaluationCriteria extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['objetive', 'grade_definitions', 'module_id'];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function grades()
    {
        return $this->morphMany(Grading::class, 'gradable');
    }

}
