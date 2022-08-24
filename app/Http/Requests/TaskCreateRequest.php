<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskCreateRequest extends FormRequest
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
            'admin' => ['required'],   
            'client' => ['required']
        ];
    }

    public function messages() 
    {
        return [
            "admin.required" => "Please select Admin!",
            "client.required" => "Please select client!"
        ];
    }
}
