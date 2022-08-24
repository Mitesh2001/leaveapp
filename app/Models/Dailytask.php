<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dailytask extends Model
{
    public $fillable = [
        'user_id',
        'project_name',
        'task_description',
        'task_hours'
    ];
}
