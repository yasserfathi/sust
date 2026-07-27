<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class WorkshopEnRequest extends FormRequest
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
                Rule::unique('workshops')->where(function ($query) {
                    $query->where('title', $this->title)
                        ->where('lang', 2)->whereNull('deleted_at');
                })->ignore(request()->route('workshop_en'))
            ],
            'lang' => 'required|string',
            'photos' => 'required|string',
            'keywords' => 'required|string',
            'workshop_date' => 'required|string',
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
