<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveGradableRequest extends FormRequest
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
            'assignment_id' => 'required|numeric|exists:assignments,id,deleted_at,NULL',
            'gradables' => 'required|array',
            'gradables.*.comments' => 'required|string',
            'gradables.*.grade' => 'required|numeric',
            'gradables.*.gradable_id' => 'required|integer',
        ];
    }
}
