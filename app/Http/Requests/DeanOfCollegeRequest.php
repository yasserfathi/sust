<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class DeanOfCollegeRequest extends FormRequest
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
            'college_id' => [
                'required',
                Rule::unique('dean_of_colleges')->where(function ($query) {
                    $query->where('college_id', $this->college_id)
                        ->where('user_id', $this->user_id)
                        ->where('start_date', $this->start_date)
                        ->whereNull('deleted_at');
                })->ignore(request()->route('dean_of_colleges'))
            ],
            'user_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
        ];
    }

    public function messages(): array
    {
        return [
            'college_id.unique' => 'duplicate',
            'end_date.after' => 'The end date must be after the start date.',
        ];
    }
}
