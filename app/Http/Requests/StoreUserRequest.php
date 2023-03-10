<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:191'],
            'username' => [
                'required', 'string', 'alpha_dash', 'max:191',
                'unique:users'
            ],
            'email' => [
                'required', 'email', 'max:255', 'unique:users'
            ],
            'password' => ['required', 'string', 'max:191'],
            'role' => ['required', 'string', 'in:Admin,Operator']
        ];
    }
}
