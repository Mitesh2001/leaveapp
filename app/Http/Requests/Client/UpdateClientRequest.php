<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('client-update');
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
            'admins_list' => ['required'],
            'project_name' => ['required'],
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
            'admins_list.required' => "Admins Field is Required !",
            'project_name.required' => "Project Name is Required !",
            'secondary_email.email' => 'Please enter valid email address!',
        ];
    }
}
