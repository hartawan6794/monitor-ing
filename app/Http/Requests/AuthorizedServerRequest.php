<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthorizedServerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

     public function rules()
    {
        return [
            'ip_address' => 'required',
        'server_name' => 'required',
        'username' => 'required',
        'password' => 'nullable|min:8',
        'port' => 'required'
        ];
    }
}
