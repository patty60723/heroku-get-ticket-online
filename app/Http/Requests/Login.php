<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class Login extends FormRequest
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
            'email'    => ['required', 'max:256'],
            'passowrd' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email Address is required.',
            'password.required' => 'Password is required'
        ];
    }
}
