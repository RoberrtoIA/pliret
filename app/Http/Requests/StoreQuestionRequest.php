<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionRequest extends FormRequest
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

    public function baseRules(): array
    {
        return [
            'question' => 'required|max:200',
            'grade_definitions' => 'required|string',
            'topic_id' => 'required|numeric|exists:topics,id,deleted_at,NULL',
        ];
    }
}
