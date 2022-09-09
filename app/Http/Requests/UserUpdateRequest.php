<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserUpdateRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'max:70',
                "regex:/^(([\sa-zA-Z'-]{1,70})|([\sа-яА-ЯЁё'-]{1,70}))$/u"
            ],
            'email' => [
                'required',
                Rule::unique('users')->ignore($this->route('id'), 'id'),
                'email:rfc,dns',
            ],
            'password' => [
                'nullable',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->symbols()
                    ->uncompromised()
                    ->numbers()
                    ->mixedCase(),
            ],
        ];
    }
}
