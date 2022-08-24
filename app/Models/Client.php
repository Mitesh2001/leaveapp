<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;
    
    protected $searchableFields = ['company_name', 'address'];

    protected $fillable = [
        'external_id',
        'name',
        'service_type',
        'project_name',
        'primary_number',
        'secondary_number',
        'primary_email',
        'secondary_email',
        'primary_contact_person', 
        'secondary_contact_person',
        'company_name',
        'company_type',
        'country_id',
        'state_id',
        'city',
        'address',
        'zipcode',
        'notes'
    ];
    
    public function users()
    {
        return $this->belongsToMany(User::class, "client_users");
    }

    public function admins()
    {
        return $this->belongsToMany(User::class, "client_admins", 'admin_id', 'client_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getCountry()
    {
        return $this->hasOne(Country::class, 'country_id', 'country_id');
    }

    public function getState()
    {
        return $this->hasOne(State::class, 'state_id', 'state_id');
    }
}
