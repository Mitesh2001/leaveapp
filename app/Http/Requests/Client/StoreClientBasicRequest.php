<?php

namespace App\Http\Requests\client;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientBasicRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('client-create');
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
            'email' => ['required', 'email'],
            'primary_number' => ['required', 'numeric', 'digits:10'], 
            // 'address' => ['required'], 
        ];
    }

    // Messages
    public function messages() 
    {
        return [
            'name.required' => "Please enter name!",
            'email.required' => "Please enter email!",
            'email.email' => 'Please enter valid email address!',
            'primary_number.required' => "Please enter primary number!",
            'primary_number.numeric' => "Please enter valid primary number!",
            'primary_number.digits' => "Please enter valid primary number!",
            // 'address.required' => "Please enter address!"
        ];
    }
}
