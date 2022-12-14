<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => ['required'],  
            'last_name' => ['required'],  
            'email' => ['email', 'required' ],  
            'primary_number' => ['digits:10', 'required'],  
            'secondary_number' => ['digits:10'],    
            'role' => ['required'],   
            'date_of_joining' => ['required'],       
        ];
    }

    // Messages
    public function messages() 
    {
        return [
            'first_name.required' => "Please enter first name!",  
            'last_name.required' => "Please enter last name!",  
            'email.required' => "Please enter email!",  
            'email.email' => "Please enter valid email!",  
            // 'email.unique' => "Email already exist!",  
            'primary_number.required' => "Please enter primary number!",  
            'primary_number.digit' => "Please enter valid primary number!",  
            'secondary_number.digit' => "Please enter valid seondary number!",  
            'secondary_number.digit' => "Please enter valid seondary number!",  
            // 'branch_id.required' => "Please select branch!",  
            'role.required' => "Please select role!",   
            'date_of_joining.required' => "Please select date of joining!",  
            // 'salary.required' => "Please enter salary!",  
            // 'working_hours.required' => "Please enter working hours!",  
        ];
    }
}
