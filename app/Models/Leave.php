<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $table = 'leaves';

    protected $fillable = [
        'external_id',
        'person_id',
        'user_id',
        'type_id',
        'start_date',
        'end_date',
        'total_days',
        'notes',
        'attachment'
    ];

    public function LeaveType()
    {
        return $this->belongsTo(LeaveType::class,"type_id");
    }

    public function leaveManage()
    {
        return $this->hasOne(LeaveManage::class,"leave_id");
    }
}
