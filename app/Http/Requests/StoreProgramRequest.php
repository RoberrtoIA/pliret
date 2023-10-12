<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProgramRequest extends FormRequest
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
        return static::baseRules();
    }

    public static function baseRules(): array
    {
        return [
            'title' => 'required|max:100',
            'description' => 'required|max:200',
            'content' => 'required|min:1',
            'tags' => 'required'
        ];
    }
}
