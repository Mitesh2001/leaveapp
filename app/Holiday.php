<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $fillable = [
        'external_id',
        'title',
        'date',
        'end_date',
        'day',
        'description'
    ];
}
