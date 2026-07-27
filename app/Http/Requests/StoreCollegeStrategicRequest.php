<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StoreCollegeStrategicRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        $response = [
            'message' => $validator->errors(),
            'status' => 409
        ];

        throw new HttpResponseException(response()->json($response));
    }

    public function rules(): array
    {
        return [
            'college_id' => 'bail|required|string',
            'lang' => 'required|string',
            'vision' => 'required|string',
            'mission' => 'required|string',
            'goals' => 'required|string',
            'keywords' => 'required|string'
        ];
    }

    public function messages(): array
    {
        return [
            'college_id.required' => 'Please select a college.',
            'college_id.exists' => 'The selected college does not exist.',
            'lang.required' => 'Language is required.',
            'lang.integer' => 'Language must be a valid integer.',
            'lang.in' => 'Language must be either Arabic (1) or English (2).',
            'keywords.max' => 'Keywords must not exceed 255 characters.',
        ];
    }

}
