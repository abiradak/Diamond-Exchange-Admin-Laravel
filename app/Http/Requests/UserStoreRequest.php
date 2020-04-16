<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\User;

class UserStoreRequest extends FormRequest
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
            'username'        => 'required|max:10',
            'name'            => 'required|max:40|min:4',
            'password'        => [
                                    'required',
                                    'string',
                                    'min:8',              // must be at least 8 characters in length
                                    'regex:/[a-z]/',      // must contain at least one lowercase letter
                                    'regex:/[A-Z]/',      // must contain at least one uppercase letter
                                    'regex:/[0-9]/',      // must contain at least one digit
                                    // 'regex:/[@$!%*#?&]/', // must contain a special character
                                 ],
            'confirmpassword' => 'required|max:30',
            'mobile'          => 'required|max:10|min:10',
            'role'            => 'required',
            'commission'      => 'required|array',
            'commission.*.comm_value' => 'required|numeric|max:100|min:0',
            'commission'      => 'required|array',
            'bet_amount'      =>  'required|array',
            'bet_amount.*.min' => 'required',
            'bet_amount.*.max' => 'required',
            'master_password' => 'required'

        ];
    }
}
