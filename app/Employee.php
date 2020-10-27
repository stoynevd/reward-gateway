<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{

    protected $fillable = [
        'uuid',
        'company',
        'bio',
        'name',
        'title',
        'avatar',
    ];
}
