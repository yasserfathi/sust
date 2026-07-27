<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;


class AlbumRequest extends FormRequest
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
            'college_id' => 'bail|required',
            'title' => [
                'required',
                Rule::unique('albums')->where(function ($query) {
                    $query->where('title', $this->title)
                        ->where('college_id', $this->college_id)
                        ->whereNull('deleted_at');
                })->ignore(request()->route('album'))
            ],
            'title_en' => [
                'required',
                Rule::unique('albums')->where(function ($query) {
                    $query->where('title_en', $this->title_en)
                        ->where('college_id', $this->college_id)
                        ->whereNull('deleted_at');
                })->ignore(request()->route('album'))
            ],
            'description' => 'required|string',
            'keywords' => 'required|string'
        ];
    }

    public function messages(): array
    {
        return [
            'title.unique' => 'duplicate',
            'title_en.unique' => 'duplicate',
        ];
    }
}
