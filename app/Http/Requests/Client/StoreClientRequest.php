<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
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
            'name' => ['required'],
            'primary_email' => ['required', 'email'],
            'secondary_email' => ['email'],
            'project_name' => ['required'],
            'admins_list' => ['required'],
            'primary_number' => ['required', 'numeric', 'digits:10'],
            'secondary_number' => ['numeric', 'digits:10']
        ];
    }

    // Messages
    public function messages() 
    {
        return [
            'name.required' => "Please enter name!",
            'primary_email.required' => "Please enter email!",
            'secondary_email.email' => 'Please enter valid email address!',
            'project_name.required' => "Project Name is Required !",
            'admins_list.required' => "Admins Field is Required !",
            'primary_number.required' => "Please enter primary number!",
            'primary_number.numeric' => "Please enter valid primary number!",
            'primary_number.digits' => "Please enter valid primary number!",
            'secondary_number.numeric' => "Please enter valid secondary number!",
            'secondary_number.digits' => "Please enter valid secondary number!",
        ];
    }
}
