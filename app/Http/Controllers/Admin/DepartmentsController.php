<?php

namespace App\Http\Controllers\Admin;

use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\DepartmentRequest;

class DepartmentsController extends Controller
{
    public function index(Request $request)
    {
        $itemsPerPage = htmlspecialchars($request->get('items') ?? 15);
        $search = htmlspecialchars($request->get('search') ?? '');

        //Department collection
        $result_departments = Department::select('id', 'college_id', 'name', 'name_en', 'active');

        if (!empty($search)) {
            $result_departments->where('name', 'like', '%' . $search . '%')
                ->orWhere('name_en', 'like', '%' . $search . '%');
        }

        $result_departments->with('college:id,name')->whereHas('college', function ($query) {
            $query->where('active', '1');
        });


        // ===================================

        //Department collection with college Name Search
        $result_college_name = Department::select('id', 'college_id', 'name', 'name_en', 'active');

        $result_college_name->with('college:id,name')->whereHas('college', function ($query) use ($search) {
            if (!empty($search)) {
                $query->where('name', 'like', '%' . $search . '%');
            }
        });

        $all = $result_departments->get()->merge($result_college_name->get());

        if ($request->exists('orderby') && $request->exists('ascend')) {
            $all = $all->sortBy([[$request->get('orderby'), $request->get('ascend')]]);
        }

        return response()->json(['result' => $all->paginate((int) $itemsPerPage)], 200);
    }

    public function show($id)
    {
        $data = Department::select('*')->where('id', $id)->first()->makeVisible(['college_id']);

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
        $data = Department::select('id', 'name')->where('college_id', $request->college_id)->where('active', 1)->orderBy('name', 'asc')->get();
        return response()->json(['departments' => $data], 200);
    }

    public function print()
    {
        $departments = Department::with('college')->orderBy('college_id')->get();
        return view('admin.departments_print', compact('departments'));
    }
}
