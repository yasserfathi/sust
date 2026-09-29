<?php

namespace App\Http\Controllers\Admin;

use App\Models\College;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\DepartmentRequest;

class DepartmentsController extends Controller
{
    public function index(Request $request)
    {
        $itemsPerPage = (int) htmlspecialchars($request->get('items') ?? 15);
        if ($itemsPerPage <= 0) {
            $itemsPerPage = 1000;
        }
        $search = htmlspecialchars($request->get('search') ?? '');
        $search = trim(preg_replace('/[+\-><\(\)~*\"@]+/', ' ', $search));

        $query = Department::select('departments.id', 'departments.college_id', 'departments.school_id', 'departments.name', 'departments.name_en', 'departments.active')
            ->with('college:id,name');

        $query->whereHas('college', function ($q) {
            $q->where('active', '1');
        });

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereRaw('MATCH(departments.name, departments.name_en) AGAINST(? IN BOOLEAN MODE)', [implode(' ', array_map(function($w) { $w = trim(preg_replace('/[+\-\><\(\)~*"@]+/', '', $w)); if (!$w) return ''; $prefixes = ['', 'ال', 'وال', 'بال', 'فال', 'لل', 'كال']; $group = []; foreach($prefixes as $p) { $group[] = $p . $w . '*'; } return '+(' . implode(' ', $group) . ')'; }, explode(' ', $search)))])
                    ->orWhereHas('college', function ($cq) use ($search) {
                        $cq->where('name', 'like', '%' . $search . '%')
                            ->orWhere('name_en', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($request->has('orderby') && $request->has('ascend')) {
            $orderBy = $request->get('orderby');
            $sortDirection = ($request->get('ascend') === 'true' || $request->get('ascend') === 'asc' || $request->get('ascend') == '1') ? 'asc' : 'desc';

            if ($orderBy === 'college.name') {
                $query->join('colleges', 'colleges.id', '=', 'departments.college_id')
                      ->orderBy('colleges.name', $sortDirection);
            } else {
                $query->orderBy('departments.' . ltrim($orderBy, 'departments.'), $sortDirection);
            }
        } else {
            $query->orderBy('departments.id', 'desc');
        }

        return response()->json(['result' => $query->paginate($itemsPerPage)], 200);
    }

    public function show($id)
    {
        $data = Department::select('*')->where('id', $id)->first();
        
        if ($data) {
            $data->makeVisible(['college_id', 'school_id']);
        }

        return response()->json(['result' => $data, 'status' => 200]);
    }

    public function store(DepartmentRequest $request)
    {
        $validator = $request->validated();

        $active = 0;
        if (isset($request->active) && (int) $request->active == 1) {
            $active = 1;
        }
        Department::create(array_merge(
            $validator,
            ['user_id' => Auth::user()->id],
            ['active' => $active],
        ));
        return response()->json(['message' => 'created', 'status' => 201]);
    }

    public function update(DepartmentRequest $request, string $id)
    {
        $record = Department::findOrFail($id);
        $active = 0;
        if (isset($request->active) && $request->active == 1) {
            $active = 1;
        }

        $record->college_id = $request->college_id;
        $record->school_id = $request->school_id;
        $record->name = $request->name;
        $record->name_en = $request->name_en;
        $record->keywords = $request->keywords;
        $record->description = $request->description;
        $record->keywords_ar = $request->keywords_ar;
        $record->description_ar = $request->description_ar;
        $record->active = $active;
        $record->user_id = Auth::user()->id;

        if ($record->isDirty()) { // if record data changed
            $record->save();
            return response()->json(['message' => 'updated', 'status' => 204]);
        }
        return response()->json(['message' => 'not changed', 'status' => 304]);
    }

    public function destroy(string $id)
    {
        $data = Department::findOrFail($id);
        $data->delete();
        return response()->json(['message' => 'deleted', 'status' => 200]);
    }

    public function list(Request $request)
    {
        $query = Department::select('id', 'name')->where('active', 1);
        
        if ($request->has('college_id') && $request->college_id) {
            $query->where('college_id', $request->college_id);
        } else {
            $authUser = Auth::user();
            if ($authUser && $authUser->role != 1) {
                // If user is not admin, only show departments from their colleges
                $userColleges = College::where('user_id', $authUser->id)->pluck('id');
                $query->whereIn('college_id', $userColleges);
            }
        }

        if ($request->has('school_id') && $request->school_id) {
            $query->where('school_id', $request->school_id);
        }
        
        $data = $query->orderBy('name', 'asc')->get();
        return response()->json(['departments' => $data], 200);
    }

    public function print()
    {
        $departments = Department::with('college')->orderBy('college_id')->get();
        return view('admin.departments_print', compact('departments'));
    }
}
