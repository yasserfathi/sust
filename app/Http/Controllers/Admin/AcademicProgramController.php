<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AcademicProgramRequest;
use Exception;
use App\Models\AcademicProgram;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AcademicProgramController extends Controller
{

    public function index(Request $request)
    {
        $itemsPerPage = $request->get('items', 15);
        $search = htmlspecialchars($request->get('search') ?? '');

        $query = AcademicProgram::select('id', 'department_id', 'program_type', 'program_name', 'program_name_en', 'credit_hours', 'active');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('program_name', 'like', '%' . $search . '%')
                    ->orWhere('program_name_en', 'like', '%' . $search . '%');
            });
        }

        $query->with(['department:id,name,college_id', 'department.college:id,name']);

        if ($request->exists('orderby') && $request->exists('ascend')) {
            $query->orderBy($request->get('orderby'), $request->get('ascend') ? 'asc' : 'desc');
        } else {
            $query->orderBy('id', 'desc');
        }

        $all = $query->paginate((int) $itemsPerPage);

        return response()->json(['result' => $all], 200);
    }

    public function show($id)
    {
        $data = AcademicProgram::with(['department:id,name,college_id', 'department.college:id,name', 'courses'])->find($id);

        if (!$data) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json(['result' => $data, 'status' => 200]);
    }

    public function store(AcademicProgramRequest $request)
    {
        $validator = $request->validated();

        $active = 0;
        if (isset($request->active) && $request->active == 1) {
            $active = 1;
        }

        $file_path = '';
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            try {
                $file = $request->file('file');
                $filename = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
                $file_path = $file->storeAs('files', $filename, 'public');
            } catch (Exception $e) {
            }
        }

        $data = array_merge(
            $validator,
            ['user_id' => Auth::user()->id],
            ['active' => $active],
            ['file' => $file_path],
            ['credit_hours' => $request->credit_hours ?? '']
        );

        AcademicProgram::create($data);
        return response()->json(['message' => 'created', 'status' => 201]);
    }

    public function update(AcademicProgramRequest $request, AcademicProgram $academicProgram)
    {
        $record = AcademicProgram::find($academicProgram->id);
        $file_path = $record->file;
        if ($request->hasFile('file')) {
            if ($request->file('file')->isValid()) {
                try {
                    $file = $request->file('file');
                    $rand = hexdec(uniqid());
                    $filename = $rand . '.' . $file->getClientOriginalExtension();
                    $file_path = $file->storeAs('files', $filename, 'public');
                    if (Str::length($record->file) > 0 && file_exists(public_path($record->file))) {
                        unlink($record->file);
                    }
                } catch (Exception $e) {
                }
            }
        }
        $active = 0;
        if (isset($request->active) && $request->active == 1) {
            $active = 1;
        }
        $record->program_name = $request->program_name;
        $record->program_name_en = $request->program_name_en;
        $record->department_id = $request->department_id;

        $record->program_type = $request->program_type;
        $record->credit_hours = $request->credit_hours ?? '';
        $record->active = $active;
        $record->file = $file_path;
        $record->user_id = Auth::user()->id;

        if ($record->isDirty()) { // if record data changed
            $record->save();
            return response()->json(['message' => 'updated', 'status' => 204]);
        }
        return response()->json(['message' => 'not changed', 'status' => 304]);
    }

    public function destroy($id)
    {
        $data = AcademicProgram::find($id);
        if (!$data) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $data->delete();
        return response()->json(['message' => 'deleted', 'status' => 200]);
    }
}
