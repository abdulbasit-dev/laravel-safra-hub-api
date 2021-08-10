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
            'name' => ['min:3', 'max:50'],
            'email' => ['required', 'email', 'regex:/gmail|outlook|yahoo/','unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'birthday' => ['date'],
            'image' => ['image', 'max:4000'],
        ];
    }
}
