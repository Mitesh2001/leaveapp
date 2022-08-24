<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeaveRequest extends FormRequest
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
            'person' => ['required'],
            'type' => ['required'],
            'start_date' => ['required'],
            'person' => ['required']
        ];
    }

    // Messages
    public function messages() 
    {
        return [
            'person.required' => "Select Person",
            'type.required' => "Select Leave Type",
            'start_date.required' => "Select Leave Start Date",
            'person.required' => "Select Person"
        ];
    }
}
