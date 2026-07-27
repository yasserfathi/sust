<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CalendarEnRequest extends FormRequest
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
            'year' => [
                'required',
                Rule::unique('calendars')->where(function ($query) {
                    $query->where('lang', 2)
                        ->where('year', $this->year)
                        ->where('college_id', $this->college_id)
                        ->whereNull('deleted_at');
                })->ignore(request()->route('calendar_en'))
            ],
            'lang' => 'sometimes',
            'file' => 'sometimes|mimetypes:application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf'
        ];
    }

    public function messages(): array
    {
        return [
            'year.unique' => 'duplicate',
            'file.mimetypes' => 'file'
        ];
    }
}
