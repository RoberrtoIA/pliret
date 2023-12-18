<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEvaluationCriteriaRequest extends FormRequest
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
            'objetive' => 'required|string',
            'grade_definitions' => 'required|string',
            'module_id' => 'required|numeric|exists:modules,id,deleted_at,NULL',
        ];
    }
}
