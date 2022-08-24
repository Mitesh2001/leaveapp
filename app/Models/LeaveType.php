<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    protected $table = 'leave_types';

    protected $fillable = ['name','description','external_id'];

    public function Leave()
    {
        return $this->hasMany(Leave::class,'type_id');
    }
}
