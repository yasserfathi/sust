<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StaffResumeRequest extends FormRequest
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
            'user_id' => ['required',
                        Rule::unique('staff_resumes')->where(function ($query){
                            $query->where('user_id',  $this->user_id)->whereNull('deleted_at');
                        })->ignore(request()->route('staff_resume'))
            ],
            'file' => 'nullable|mimetypes:application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf',
            'file_en' => 'nullable|mimetypes:application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.unique' => 'duplicate',
            'file.mimetypes' => 'file',
            'file_en.mimetypes' => 'file_en',
        ];
    }
}
