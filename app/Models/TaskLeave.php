<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskLeave extends Model
{
    public $fillable = ['user_id','leave_type'];
}
