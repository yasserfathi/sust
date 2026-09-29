<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class SectionRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sections')->where(function ($query) {
                    $query->where('name', $this->name)
                        ->where('department_id', $this->department_id)
                        ->whereNull('deleted_at');
                })->ignore(request()->route('section'))
            ],
            'name_en' => 'required|string|max:255',
            'keywords' => 'required|string|max:1000',
            'description' => 'required|string',
            'keywords_ar' => 'required|string|max:1000',
            'description_ar' => 'required|string',
            'department_id' => 'required|integer|exists:departments,id',
            
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'duplicate',
        ];
    }
}
