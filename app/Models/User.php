<?php
namespace App\Models;

use Fenos\Notifynder\Notifable;
use Illuminate\Notifications\Notifiable;
use App\Models\Client;
use App\Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Api\v1\Models\Token;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, EntrustUserTrait,  SoftDeletes;

    public function restore()
    {
        $this->restoreA();
        $this->restoreB();
    }

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';
 
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'external_id',
        'name',
        'first_name',
        'last_name', 
        'email',
        'password', 
        'primary_number',
        'secondary_number',
        'expertise',   
        'date_of_joining', 
        'address', 
        'working_hours', 
        'profile_pic',
        'employer_id'
    ];

    public function clients()
    {
        return $this->belongsToMany(Client::class, "client_users")->latest();
    }

    public function employer()
    {
        return $this->belongsTo(User::class, "id");
    }

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'password_confirmation'];

    protected $primaryKey = 'id';

    public function tasks()
    {
        return $this->hasMany(Task::class, "done_by", "id")->latest();
    }

    public function userRole()
    {
        return $this->hasOne(RoleUser::class, 'user_id', 'id');
    }

    public function tokens()
    {
        return $this->hasMany(Token::class, 'user_id', 'id');
    }

}
