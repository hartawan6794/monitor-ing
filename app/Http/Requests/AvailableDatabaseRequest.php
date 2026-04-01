<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AvailableDatabaseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'server_id' => 'required',
            'db_name' => 'required',
            'description' => 'nullable',
            'expired_at' => 'required'
        ];
    }
}
