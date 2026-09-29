<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class PageRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Return validation errors as json response
     *
     * @param Validator $validator
     */
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
            'college_id' => ['bail', 'required', 'integer', 'exists:colleges,id'],
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],

            'lang' => [
                'required',
                'string',
                'max:5',
                Rule::unique('pages')->where(
                    fn($query) =>
                    $query->where('slug', $this->route('slug')) // Rationale: الوصول للـ slug من الـ Route مباشرة أضمن من الـ segment.
                        ->where('lang', $this->lang)           // Rationale: إزالة DB::raw لمنع SQL Injection.
                        ->where('college_id', $this->college_id)
                        ->whereNull('deleted_at')
                )->ignore($this->route('id') ?? $this->route('page')) // Rationale: دعم اختلاف مسميات الـ Parameter في الـ Resource.
            ],

            'keywords' => ['required', 'string', 'max:255'],
            'detail_portion' => ['required', 'string', 'max:255'],
            'detail' => ['required', 'string'],

            // Rationale: استخدام الـ Validation المدمج للـ Mimes والـ Max size لضمان أمن المرفقات.
            'file' => ['sometimes', 'file', 'mimes:doc,docx,pdf', 'max:10240'],
            'img' => ['sometimes', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'file.mimetypes' => 'file',
            'img.mimes' => 'img'
        ];
    }
}
