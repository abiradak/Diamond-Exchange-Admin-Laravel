<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MatchRequest extends FormRequest
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
            'event_id' => 'required',
            'market_id' => 'required',
            'teams' => 'required|array',
            'competition_id' => 'required',
            'date' => 'required',
            'shortname' => 'required',
            'sport_type' => 'required',
            'sport_id' => 'required',
            'name' => 'required',
        ];
    }
}
