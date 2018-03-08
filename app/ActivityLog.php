<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = ['calling_class', 'calling_function', 'level', 'message'];
}
