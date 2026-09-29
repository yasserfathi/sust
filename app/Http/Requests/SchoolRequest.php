<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class SchoolRequest extends FormRequest
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
                Rule::unique('schools')->where(function ($query) {
                    $query->where('name', $this->name)
                        ->where('college_id', $this->college_id)
                        ->whereNull('deleted_at');
                })->ignore(request()->route('school'))
            ],
            'name_en' => 'required|string|max:255',
            'keywords' => 'required|string|max:1000',
            'description' => 'required|string',
            'keywords_ar' => 'required|string|max:1000',
            'description_ar' => 'required|string',
            'college_id' => 'required|integer|exists:colleges,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'duplicate',
        ];
    }
}
