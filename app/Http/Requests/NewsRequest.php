<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class NewsRequest extends FormRequest
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
            'college_id' => 'bail|required|string',
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('news')->where(function ($query){
                    $query->where('title', $this->title)
                    ->where('lang',1)->whereNull('deleted_at');
                })->ignore($this->route('news'))
            ],
            'lang' => 'required|string',
            'keywords' => 'required|string',
            'news_date' => 'required|string',
            'detail_portion' => 'required|string',
            'detail' => 'required|string',
            'file' => 'sometimes|mimetypes:application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf',
        ];
    }

    public function messages(): array
    {
        return [
            'title.unique' => 'duplicate',
            'file.mimetypes' => 'file'
        ];
    }
}
