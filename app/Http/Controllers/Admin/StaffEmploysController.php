<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaffEmploy;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StaffEmployRequest;

class StaffEmploysController extends Controller
{

    public function store(StaffEmployRequest $request)
    {
        $validator = $request->validated();

        StaffEmploy::create(array_merge(
            $validator,
            ['auth_id' => Auth::user()->id]
        ));

        return response()->json(['message' => 'created', 'status' => 201]);
    }

    public function show(StaffEmploy $staffEmploy)
    {
        $data = StaffEmploy::where('user_id', $staffEmploy->id)->with(['department:id,name,college_id', 'department.college:id,name'])
            ->select('id', 'job_title', 'job_title_en', 'rank', 'rank_en', 'hire_date', 'specialty', 'subspecialty', 'specialty_en', 'subspecialty_en', 'department_id')->orderBy('hire_date', 'desc')->get();
        return response()->json(['result' => $data, 'status' => 200]);
    }

    public function update(StaffEmployRequest $request, StaffEmploy $staffEmploy)
    {
        $record = StaffEmploy::findOrFail($staffEmploy->id);

        if (Auth::user()->role == 3 && $request->user_id === null) {
            $request->request->add(['user_id' => Auth::user()->id]);
        }

        $record->department_id = $request->department_id;
        $record->job_title = $request->job_title;
        $record->job_title_en = $request->job_title_en;
        $record->rank = $request->rank;
        $record->rank_en = $request->rank_en;
        $record->hire_date = $request->hire_date;
        $record->specialty = $request->specialty;
        $record->subspecialty = $request->subspecialty;
        $record->specialty_en = $request->specialty_en;
        $record->subspecialty_en = $request->subspecialty_en;

        if ($record->isDirty()) { // if record data changed
            $record->auth_id = Auth::user()->id;
            $record->save();
            return response()->json(['message' => 'updated', 'status' => 204]);
        }
        return response()->json(['message' => 'not changed', 'status' => 304]);
    }

    public function destroy(StaffEmploy $staffEmploy)
    {
        StaffEmploy::find($staffEmploy->id)->delete();
        return response()->json(['message' => 'deleted', 'status' => 200]);
    }
}
