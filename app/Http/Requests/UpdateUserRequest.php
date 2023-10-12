<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateUserRequest extends StoreUserRequest
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
        return replaceRequiredByFilledRules(array_merge(
            parent::baseRules(),
            [
                'email' => [
                    'filled|email', Rule::unique('email')->ignore($this->id)
                ],
                'roles' => 'filled|array'
            ]
        ));
    }
}
