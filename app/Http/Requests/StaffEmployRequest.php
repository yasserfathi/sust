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
            'job_title' => 'required|string',
            'job_title_en' => 'required|string',
            'rank' => 'required|string',
            'rank_en' => 'required|string',
            'hire_date' => 'required|date',
            'specialty' => 'required|string',
            'subspecialty' => 'required|string',
            'specialty_en' => 'required|string',
            'subspecialty_en' => 'required|string',
            'user_id' => 'required|string'
        ];
    }
}
