<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientUser extends Model
{
    protected $fillable = ['user_id','client_id'];
}
