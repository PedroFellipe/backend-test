<?php

namespace App\Http\Requests;

class EventRequest extends BaseRequest
{
    /**
     * Register* Determine if the user is authorized to make this request.
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

        $todayDate = date('m/d/Y');

        return [

            'name' => 'required|string|max:150',
            'description' => 'required|string|max:255',
            'date' => 'required|date_format:Y-m-d|after_or_equal:'.$todayDate,
            'time' => 'required|date_format:h:i',
            'place' => 'required|string|max:255',

        ];
    }
}
