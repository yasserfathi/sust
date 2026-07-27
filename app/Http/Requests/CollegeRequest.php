<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CollegeRequest extends FormRequest
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
                Rule::unique('colleges')->where(function ($query) {
                    $query->where('name', $this->name)
                        ->whereNull('deleted_at');
                })->ignore(request()->route('college'))
            ],
            'name_en' => [
                'required',
                Rule::unique('colleges')->where(function ($query) {
                    $query->where('name_en', $this->name_en)
                        ->whereNull('deleted_at');
                })->ignore(request()->route('college'))
            ],
            'college_type' => 'required',
            'logo' => 'sometimes|mimes:jpeg,png,jpg',
            'logo_en' => 'sometimes|mimes:jpeg,png,jpg',
            'banner' => 'sometimes|mimes:jpeg,png,jpg'
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'duplicate',
            'name_en.unique' => 'duplicate',
        ];
    }
}
