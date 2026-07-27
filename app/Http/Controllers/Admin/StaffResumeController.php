<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff_resume;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StaffResumeRequest;
use Illuminate\Support\Str;

class StaffResumeController extends Controller
{

    public function index(Request $request)
    {
        $itemsPerPage = htmlspecialchars($request->get('items') ?? 15);

        if ($itemsPerPage < 0) {
            $itemsPerPage = 0;
        }

        $search = htmlspecialchars($request->get('search') ?? '');

        $result = Staff_resume::select('id', 'file', 'file_en', 'user_id')
            ->whereHas(
                'user',
                function ($query) use ($search) {
                    if (!empty($search)) {
                        $query->where('name', 'like', '%' . $search . '%');
                    }
                },
            )->whereHas('user.staff_latest.department.college')
            ->with('user:id,name');

        if (Auth::user()->role == 3) {
            $result->where('user_id', Auth::user()->id);
        }

        if ($itemsPerPage == 0) {
            $itemsPerPage = count($result);
        }

        return response()->json(['result' => $result->paginate((int)$itemsPerPage)], 200);
    }

    public function show($id)
    {
        $edit_data = Staff_resume::select('file', 'file_en', 'user_id')
            ->with([
                'user' => function ($query) {
                    $query->select('id', 'name');
                },
                'user.staff_latest' => function ($query) {
                    $query->select('id', 'user_id', 'department_id');
                },
                'user.staff_latest.department' => function ($query) {
                    $query->select('id', 'college_id', 'name');
                },
                'user.staff_latest.department.college' => function ($query) {
                    $query->select('id', 'name');
                },
            ])
            ->whereHas('user.staff_latest.department')
            ->where('id', DB::raw('"' . $id . '"'))->first();

        /* $edit_data = Staff_resume::select('file', 'file_en', 'user_id')
            ->with([
                'user:id,name',
                'user.staff_latest:id,user_id,department_id',
                'user.staff_latest.department:id,college_id,name',
                'user.staff_latest.department.college:id,name'
            ])->get(); */
        return response()->json(['result' => $edit_data, 'status' => 200]);
    }

    public function store(StaffResumeRequest $request)
    {
        $validator = $request->validated();
        $file_path = $file_path_en = '';
        $db_files = Staff_resume::select('file', 'file_en')->where('user_id', '=', DB::raw('"' . $request->user_id . '"'))->first();

        if ($db_files != null) {
            $file_path = $db_files->file;
            $file_path_en = $db_files->file_en;
        }

        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            try {
                $file = $request->file('file');
                $rand = hexdec(uniqid());
                $filename = $rand . '.' . $file->getClientOriginalExtension();
                if ($file_path != '') {
                    unlink('resumes/' . $file_path);
                }
                $file_path = $file->storeAs('resumes', $filename, 'public');
            } catch (Exception $e) {
            }
        }

        if ($request->hasFile('file_en') && $request->file('file_en')->isValid()) {
            try {
                $file_en = $request->file('file_en');
                $rand = hexdec(uniqid());
                $filename = $rand . '.' . $file_en->getClientOriginalExtension();
                if ($file_path_en != '') {
                    unlink('resumes/' . $file_path_en);
                }
                $file_path_en = $file_en->storeAs('resumes', $filename, 'public');
            } catch (Exception $e) {
            }
        }

        Staff_resume::create(array_merge(
            $validator,
            ['auth_id' => Auth::user()->id],
            ['file' => $file_path],
            ['file_en' => $file_path_en],
        ));
        return response()->json(['message' => 'created', 'status' => 201]);
    }

    public function update(StaffResumeRequest $request, string $id)
    {

        $record = Staff_resume::findOrFail($id);

        $file_path = $record->file;
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            try {
                $file = $request->file('file');
                $rand = hexdec(uniqid());
                $filename = $rand . '.' . $file->getClientOriginalExtension();
                if (Str::length($file_path) > 0 && file_exists(public_path($file_path))) {
                    unlink($file_path);
                }
                $file_path = $file->storeAs('resumes', $filename, 'public');
            } catch (Exception $e) {
            }
        }

        $file_path_en = $record->file_en;
        if ($request->hasFile('file_en') && $request->file('file_en')->isValid()) {
            try {
                $file_en = $request->file('file_en');
                $rand = hexdec(uniqid());
                $filename = $rand . '.' . $file_en->getClientOriginalExtension();
                if (Str::length($file_path_en) > 0 && file_exists(public_path($file_path_en))) {
                    unlink($file_path_en);
                }
                $file_path_en = $file_en->storeAs('resumes', $filename, 'public');
            } catch (Exception $e) {
            }
        }

        $record->user_id = $request->user_id;
        $record->file = $file_path;
        $record->file_en = $file_path_en;
        $record->auth_id = Auth::user()->id;

        if ($record->isDirty()) { // if record data changed
            $record->save();
            return response()->json(['message' => 'updated', 'status' => 204]);
        } else {
            return response()->json(['message' => 'not changed', 'status' => 304]);
        }
    }

    public function destroy(Staff_resume $staff_resume)
    {
        $data = Staff_resume::findOrFail($staff_resume->id);

        if (Str::length($data->file) > 0 && file_exists(public_path($data->file))) {
            unlink($data->file);
        }

        if (Str::length($data->file_en) > 0 && file_exists(public_path($data->file_en))) {
            unlink($data->file_en);
        }

        $data->delete();
        return response()->json(['message' => 'deleted', 'status' => 200]);
    }
}
