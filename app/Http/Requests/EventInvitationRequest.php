<?php

namespace App\Http\Requests;

class EventInvitationRequest extends BaseRequest
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
        if ($this->getMethod() == 'PUT') {
            return [
                'status' => 'required|in:confirmed,rejected,awaiting_confirmation'
            ];
        }


        if($this->type === 'all'){
            return [];
        }

        return [
            'users_id' => 'required|array',
            'users_id.*' => 'required|integer',
        ];
    }
}
