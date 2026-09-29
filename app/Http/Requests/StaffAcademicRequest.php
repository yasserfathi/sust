<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StaffAcademicRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if (auth()->check() && auth()->user()->role != 1) {
            $this->merge([
                'user_id' => auth()->user()->id,
            ]);
        }
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
            'item_val' => ['bail','required',
                Rule::unique('staff_academics')->where(function ($query){
                    $query->where('item',  $this->segment(3))
                            ->where('lang', $this->lang)
                            ->where('item_val',  $this->item_val);

                            if (!empty($this->url)) {
                                $query->where('url',  $this->url);
                            }

                            $query->where('user_id',  $this->user_id)->whereNull('deleted_at');
                })->ignore(request()->route('id'))
            ],
            'user_id' => 'required',
            'type' => 'nullable|in:1,2,3',
            'file' => 'nullable|mimetypes:application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf',
            'img' => 'nullable|mimes:jpeg,png,jpg'
        ];
    }

    public function messages(): array
    {
        return [
            'item_val.unique' => 'duplicate',
            'file.mimetypes' => 'file',
            'img.mimes' => 'img'
        ];
    }
}
