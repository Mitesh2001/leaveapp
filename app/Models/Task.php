<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = "tasks";

    protected $fillable = [
        'id',
        'external_id',
        'title',
        'leave_id',
        'behalf_of',
        'client_id',
        'done_by',
        'description',
        'time_taken',
        'project_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "done_by");
    }
}
