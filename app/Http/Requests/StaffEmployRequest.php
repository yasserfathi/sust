<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StaffEmployRequest extends FormRequest
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
            'department_id' => 'bail|required',
            'grade' => 'required|string',
            'grade_en' => 'required|string',
            'hire_date' => 'required|date',
            'specialty' => 'nullable|string',
            'subspecialty' => 'nullable|string',
            'specialty_en' => 'nullable|string',
            'subspecialty_en' => 'nullable|string',
            'user_id' => 'required|string'
        ];
    }
}
