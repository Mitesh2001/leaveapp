<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLeaveRequest extends FormRequest
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
            'type' => ['required'],
            'start_date' => ['required'],
            'end_date' => ['required'],
            'alternate_person' => ['required']
        ];
    }

    // Messages
    public function messages() 
    {
        return [
            'type.required' => "Select Leave Type",
            'start_date.required' => "Select Leave Start Date",
            'end_date.required' => "Select Leave End Date",
            'alternate_person.required' => "Select Leave Aternate Person"
        ];
    }
}
