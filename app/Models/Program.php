<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Program extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['title', 'description', 'content'];

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function modules()
    {
        return $this->hasMany(Module::class);
    }

    public function executions()
    {
        return $this->hasMany(Execution::class);
    }
}
