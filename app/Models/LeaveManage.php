<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveManage extends Model
{
    protected $fillable = [
        'external_id',
        'leave_id',
        'email_template_id',
        'clients_list',
        'alternate_person',
    ];

    public function leave()
    {
        return $this->hasOne(Leave::class, "id","leave_id")->latest();
    }
}
