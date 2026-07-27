<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class AcademicProgramRequest extends FormRequest
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
            'department_id' => 'bail|required|integer',
            'program_type' => 'required|integer',
            'program_name' => [
                'required',
                Rule::unique('academic_programs')
                    ->whereNull('deleted_at')
                    ->ignore($this->route('academic_program')),
            ],
            'program_name_en' => [
                'required',
                Rule::unique('academic_programs')
                    ->whereNull('deleted_at')
                    ->ignore($this->route('academic_program')),
            ],
            'credit_hours' => 'nullable|string',
            'active' => 'boolean',
            'file' => 'sometimes|mimetypes:application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf',
        ];
    }

    public function messages(): array
    {
        return [
            'program_name.unique' => 'program_name.duplicate',
            'program_name_en.unique' => 'program_name_en.duplicate',
            'file.mimetypes' => 'file'
        ];
    }
}
