<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ability extends Model
{
    public $timestamps = false;

    protected $fillable = ['name'];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
