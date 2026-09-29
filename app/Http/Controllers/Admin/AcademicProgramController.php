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
        $search = trim(preg_replace('/[+\-><\(\)~*\"@]+/', ' ', $search));

        $query = AcademicProgram::select('id', 'department_id', 'program_type', 'program_name', 'program_name_en', 'NOOFYEARSNO', 'NOOFSEM', 'active');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereRaw('MATCH(program_name, program_name_en) AGAINST(? IN BOOLEAN MODE)', [implode(' ', array_map(function($w) { $w = trim(preg_replace('/[+\-\><\(\)~*"@]+/', '', $w)); if (!$w) return ''; $prefixes = ['', 'ال', 'وال', 'بال', 'فال', 'لل', 'كال']; $group = []; foreach($prefixes as $p) { $group[] = $p . $w . '*'; } return '+(' . implode(' ', $group) . ')'; }, explode(' ', $search)))]);
            });
        }

        $query->with(['department:id,name,college_id,school_id', 'department.college:id,name', 'department.school:id,name']);

        $authUser = \Illuminate\Support\Facades\Auth::user();
        if ($authUser && $authUser->role != 1 && $authUser->is_college_rep) {
            $collegeId = $authUser->staff_latest_by_id?->department?->college_id;
            if ($collegeId) {
                $query->whereHas('department', function ($q) use ($collegeId) {
                    $q->where('college_id', $collegeId);
                });
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        if ($request->exists('orderby') && $request->exists('ascend')) {
            $query->orderBy($request->get('orderby'), in_array(strtolower(trim($request->get('ascend') ?? '')), ['asc', 'true', '1']) ? 'asc' : 'desc');
        } else {
            $query->orderByDesc('id');
        }

        $all = $query->paginate((int) $itemsPerPage);

        return response()->json(['result' => $all], 200);
    }

    public function show($id)
    {
        $data = AcademicProgram::with(['department:id,name,college_id,school_id', 'department.college:id,name', 'department.school:id,name', 'courses'])->find($id);

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
                return response()->json(['message' => 'Error processing file: ' . $e->getMessage(), 'status' => 500], 500);
            }
        }

        $data = array_merge(
            $validator,
            ['user_id' => Auth::user()->id],
            ['active' => $active],
            ['file' => $file_path],
            ['NOOFYEARSNO' => $request->NOOFYEARSNO ?? null],
            ['NOOFSEM' => $request->NOOFSEM ?? null]
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
                        unlink(public_path($record->file));
                    }
                } catch (Exception $e) {
                return response()->json(['message' => 'Error processing file: ' . $e->getMessage(), 'status' => 500], 500);
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
        $record->NOOFYEARSNO = $request->NOOFYEARSNO ?? null;
        $record->NOOFSEM = $request->NOOFSEM ?? null;
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
        if (Str::length($data->file) > 0 && file_exists(public_path($data->file))) {
            unlink(public_path($data->file));
        }
        $data->delete();
        return response()->json(['message' => 'deleted', 'status' => 200]);
    }
}
