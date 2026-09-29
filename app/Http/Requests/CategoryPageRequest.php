<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CategoryPageRequest extends FormRequest
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
                'string',
                'max:255',
                Rule::unique('category_pages')->where(function ($query) {
                    $query->where('title', $this->title)
                        ->where('category_id', $this->category_id)
                        ->whereNull('deleted_at');
                })->ignore(request()->route('category_page'))
            ],
            'url' => 'required',
            'category_id' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'title.unique' => 'duplicate',
        ];
    }
}
