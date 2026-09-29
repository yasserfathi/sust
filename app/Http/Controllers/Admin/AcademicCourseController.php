<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicCourse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AcademicCourseController extends Controller
{
    public function index(Request $request)
    {
        $itemsPerPage = $request->get('items', 15);
        $search = htmlspecialchars($request->get('search') ?? '');
        $search = trim(preg_replace('/[+\-><\(\)~*\"@]+/', ' ', $search));

        $query = AcademicCourse::with('program:id,program_name,program_name_en');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereRaw('MATCH(course_title, course_code) AGAINST(? IN BOOLEAN MODE)', [implode(' ', array_map(function($w) { $w = trim(preg_replace('/[+\-\><\(\)~*"@]+/', '', $w)); if (!$w) return ''; $prefixes = ['', 'ال', 'وال', 'بال', 'فال', 'لل', 'كال']; $group = []; foreach($prefixes as $p) { $group[] = $p . $w . '*'; } return '+(' . implode(' ', $group) . ')'; }, explode(' ', $search)))]);
            });
        }

        if ($request->has('program_id')) {
            $query->where('program_id', $request->program_id);
        }

        if ($request->exists('orderby') && $request->exists('ascend')) {
            $query->orderBy($request->get('orderby'), in_array(strtolower(trim($request->get('ascend') ?? '')), ['asc', 'true', '1']) ? 'asc' : 'desc');
        } else {
            $query->orderBy('course_id', 'desc');
        }

        $all = $query->paginate((int) $itemsPerPage);

        return response()->json(['result' => $all], 200);
    }

    public function show($id)
    {
        $data = AcademicCourse::with('program:id,program_name,program_name_en')->find($id);

        if (!$data) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json(['result' => $data, 'status' => 200]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'program_id' => 'required|exists:academic_programs,id',
            'course_title' => 'required|string|max:400',
            'course_code' => 'required|string|max:400',
            'course_hours' => 'required|string|max:100',
            'year' => 'required|integer',
            'semester' => 'required|integer',
            'lang' => 'integer|in:1,2',
            'course_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,images|max:10240'
        ]);

        $file_path = '';
        if ($request->hasFile('course_file') && $request->file('course_file')->isValid()) {
            try {
                $file = $request->file('course_file');
                $filename = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
                $file_path = $file->storeAs('files', $filename, 'public');
            } catch (Exception $e) {
            }
        }

        $data = $request->all();
        $data['course_file'] = $file_path; // Override with path

        AcademicCourse::create($data);
        return response()->json(['message' => 'created', 'status' => 201]);
    }

    public function update(Request $request, $id)
    {
        $record = AcademicCourse::find($id);
        if (!$record)
            return response()->json(['message' => 'Not found'], 404);

        $request->validate([
            'program_id' => 'required|exists:academic_programs,id',
            'course_title' => 'required|string|max:400',
            'course_code' => 'required|string|max:400',
            'course_hours' => 'required|string|max:100',
            'year' => 'required|integer',
            'semester' => 'required|integer',
            'lang' => 'integer|in:1,2',
            'course_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,images|max:10240'
        ]);

        $file_path = $record->course_file;
        if ($request->hasFile('course_file') && $request->file('course_file')->isValid()) {
            try {
                $file = $request->file('course_file');
                $filename = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
                $file_path = $file->storeAs('files', $filename, 'public');

                if (Str::length($record->course_file) > 0 && file_exists(public_path($record->course_file))) {
                    unlink(public_path($record->course_file));
                }
            } catch (Exception $e) {
            }
        }

        $record->fill($request->except(['course_file']));
        $record->course_file = $file_path;

        if ($record->isDirty()) {
            $record->save();
            return response()->json(['message' => 'updated', 'status' => 204]);
        }
        return response()->json(['message' => 'not changed', 'status' => 304]);
    }

    public function destroy($id)
    {
        $data = AcademicCourse::find($id);
        if (!$data) {
            return response()->json(['message' => 'Not found'], 404);
        }

        if (Str::length($data->course_file) > 0 && file_exists(public_path($data->course_file))) {
            unlink(public_path($data->course_file));
        }

        $data->delete();
        return response()->json(['message' => 'deleted', 'status' => 200]);
    }
}
