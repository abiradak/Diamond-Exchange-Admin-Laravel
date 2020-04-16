<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MarketRequest extends FormRequest
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
            'data' =>'required|array',
            'data.*.market_id' => 'required',
            'data.*.event_id' => 'required',
            'data.*.name' => 'required',
            'data.*.max_bet' => 'required',
            'data.*.min_bet' => 'required',
            'data.*.commission' => 'required'
        ];
    }
}
