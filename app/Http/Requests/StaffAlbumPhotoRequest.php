<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Models\Department;

class StaffAlbumPhotoRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    
    protected function prepareForValidation()
    {
        if (auth()->check() && auth()->user()->role != 1) {
            $mergeData = ['user_id' => auth()->user()->id];
            
            $userDept = Department::whereHas('staff', function ($q) {
                $q->where('user_id', auth()->user()->id);
            })->first();
            
            if ($userDept) {
                $mergeData['department_id'] = $userDept->id;
                $mergeData['college_id'] = $userDept->college_id;
            }
            
            $this->merge($mergeData);
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
            'user_id' => 'required',
            'title' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'img' => 'required|mimes:jpeg,png,jpg,gif'
        ];
    }

    public function messages(): array
    {
        return [
            'img.mimes' => 'img'
        ];
    }
}
