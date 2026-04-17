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
            'user_id' => 'required|exists:users,id',
            'package_type' => 'required|string',
            'server_id' => 'required',
            'db_name' => 'required',
            'description' => 'nullable',
            'expired_at' => 'nullable' // Changed to nullable so users can have permanent DB access
        ];
    }
}
