<?php

namespace App\Http\Controllers\admin;

use App\Models\College;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    public function index()
    {
        $data =  array();
		$data = Department::with('college')->get();
		$colleges = College::select('id','name')->get();
		return view('admin.departments')->with('data', $data)->with('colleges',$colleges);
    }
	
	public function store(Request $request)
	{
		$data = Department::with('college')->select('name')->whereColumn([['college_id', '=', DB::raw('"'.$request->college_id.'"')],
                ['name', '=', DB::raw('"'.$request->name.'"')],['name_en', '=', DB::raw('"'.$request->name_en.'"')]])->get();
		if($data->count() == 0)
		{
			$active = 0;
			if(isset($request->active) && $request->active ==1) {
				$active = 1;
			}
			$validator = Validator::make($request->all(), [
				'college_id' => 'required|string',
				'name' => 'required|string',
				'name_en' => 'required|string',
			]);

			Department::create(array_merge($validator->validated(),
				['user_id' => Auth::user()->id],
				['active' => $active],
			));
			return "success";
		}
		else
		return "error";
	}

    public function show(Department $department)
    {
        $edit_data = $department::with('college')->whereColumn([['id', '=', DB::raw('"'.$department->id.'"')]])->first();
		if($edit_data != '')
		{
            $data = $department::with('college')->get();
		    $colleges = College::select('id','name')->get();
		    return view('admin.departments')->with('data', $data)->with('colleges',$colleges)->with('edit_data',$edit_data);
		}
		else
		return redirect()->route('department.index');
    }

    public function update(Request $request, Department $department)
    {
        $data = $department::with('college')->select('name')->whereColumn([['college_id', '=', DB::raw('"'.$request->college_id.'"')],
                ['name', '=', DB::raw('"'.$request->name.'"')],['name_en', '=', DB::raw('"'.$request->name_en.'"')],
				['active', '=', DB::raw('"'.$request->active.'"')]])->get();
		if($data->count() == 0)
		{
			$active = 0;
			if(isset($request->active) && $request->active ==1) {
				$active = 1;
			}

			$dept = $department::find($request->id);
			$dept->college_id = $request->college_id;
			$dept->name = $request->name;
			$dept->name_en = $request->name_en;
			$dept->active = $active;
			$dept->user_id = Auth::user()->id;
			$dept->save();
			return "success";
		}
		else
		return "error";
    }

    public function destroy(Department $department)
    {
		$department::find($department->id)->delete();
		return redirect()->route('department.index');
    }

	public function list(Request $request)
    {
        $str = '';
        if ($request->id != '') {
            $data = Department::where([['college_id', '=', DB::raw('"' . $request->id . '"')]])->get();
            $str = '<option value="">اختر القسم</option>';
            foreach ($data as $key) {
                $str .= '<option value="' . $key->id . '">' . $key->name . '</option>';
            }
        }
        return $str;
    }
}