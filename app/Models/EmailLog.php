<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Builder;

class EmailLog extends Model
{


    protected $table = "email_log";

    protected $fillable = [
        'template_id',
        'email_subject',
        'client_id', 
        'client_email',
        'from_email', 
        'from_name',    
        'email_json', 
        'template_json'  
    ];
}
