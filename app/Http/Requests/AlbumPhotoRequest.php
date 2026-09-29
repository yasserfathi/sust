<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class AlbumPhotoRequest extends FormRequest
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
            'album_id' => 'required|string',
            'title' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'img' => 'nullable|mimes:jpeg,png,jpg',
        ];
    }

    public function messages(): array
    {
        return [
            'img.mimes' => 'img'
        ];
    }
}
