<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;

class UserRequest extends FormRequest
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

        throw new HttpResponseException(response()->json($response, 409));
    }

    public function rules(): array
    {
        return [
            'name' => 'bail|required',
            'name_en' => 'bail|required',
            // 'email' => ['required', Rule::unique('users')->ignore(request()->route('user'))],
            'email' => 'nullable|string|email',
            // 'univ_no' => ['required', Rule::unique('users')->ignore(request()->route('user'))],
            'univ_no' => 'nullable|string',
            'password' => 'nullable',
            'role' => 'required',
            'phone' => ['required', Rule::unique('users')->ignore(request()->route('user'))],
            'active' => 'required',
            'img' => 'nullable|mimes:jpeg,png,jpg'
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'duplicate',
            'univ_no.unique' => 'duplicate',
            'phone.unique' => 'duplicate',
            'img.mimes' => 'img'
        ];
    }
}
