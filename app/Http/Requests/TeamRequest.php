<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\model\Team;

class TeamRequest extends FormRequest
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
            'selection_id' => 'required',
            'name' => 'required',
            'short_name' => 'required'
        ];
        
    }
}
