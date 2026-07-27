<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CategoryRequest extends FormRequest
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
            'title' => [
                'required',
                Rule::unique('categories')->where(function ($query) {
                    $query->where('title', $this->title)
                        ->whereNull('deleted_at');
                })->ignore(request()->route('category'))
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.unique' => 'duplicate',
        ];
    }
}
