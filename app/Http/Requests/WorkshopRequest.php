<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Models\Department;

class WorkshopRequest extends FormRequest
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
            'college_id' => 'bail|required|string',
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('workshops')->where(function ($query) {
                    $query->where('title', $this->title)
                        ->where('lang', 1)->whereNull('deleted_at');
                })->ignore(request()->route('workshop'))
            ],
            'type' => 'required|in:1,2,3',
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
