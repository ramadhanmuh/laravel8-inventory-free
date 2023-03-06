<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
        $id = $this->segment(count($this->segments()));
        
        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required', 'string', 'alpha_dash', 'max:255',
                "unique:users,username,$id"
            ],
            'email' => [
                'required', 'email', 'max:255', "unique:users,email,$id"
            ],
            'password' => ['nullable', 'string', 'max:255'],
            'role' => ['required', 'string', 'in:Admin,Operator']
        ];
    }
}
