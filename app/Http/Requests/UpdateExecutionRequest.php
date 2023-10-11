<?php

namespace App\Http\Requests;

class UpdateExecutionRequest extends StoreExecutionRequest
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
        return replaceRequiredByFilledRules(
            collect(parent::baseRules())->only(['start_date', 'end_date'])->all()
        );
    }
}
