<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Storage;

class Academic_ProgramsController extends Controller
{

	public function index(Request $request)
	{
		$data =  array();
		$data['data'] = \DB::table('academic_programs')->get();
		$data['colleges'] = \DB::table('colleges')->select('college_id','college_name')->get();
		return view('admin.academic_programs',compact("data"));
	}

	public function store(Request $request)
	{
        $data = \DB::table('academic_programs')->whereColumn([['program_name', '=', \DB::raw('"'.$request->name.'"')],['program_name_en', '=', \DB::raw('"'.$request->name_en.'"')],['college_id', '=', \DB::raw('"'.$request->college_name.'"')]
                ,['program_type', '=', \DB::raw('"'.$request->program_type.'"')]])->get();
		if($data->count() == 0)
		{
            \DB::table('academic_programs')->insert(array('college_id'  => \DB::raw('"'.$request->college_name.'"'),'program_name'  => \DB::raw('"'.$request->name.'"'),'program_name_en'  => \DB::raw('"'.$request->name_en.'"')
            ,'program_type'  => \DB::raw('"'.$request->program_type.'"')));
			return "success";
		}
		else
		return "error";
	}

	public function show(Request $request,$id)
	{
		$edit_data = \DB::table('academic_programs')->whereColumn([['program_id', '=', \DB::raw('"'.$id.'"')]])->first();
		if($edit_data != '')
		{
			$data =  array();
			$data['edit_data'] =  $edit_data;
			$data['data'] = \DB::table('academic_programs')->get();
			$data['colleges'] = \DB::table('colleges')->select('college_id','college_name')->get();
			return view('admin.academic_programs',compact("data"));
		}
		else
		return redirect()->route('academic_programs_index');
	}

	public function update(Request $request)
	{
		$data = \DB::table('academic_programs')->select('program_name')->whereColumn([['program_name', '=', \DB::raw('"'.$request->name.'"')],
		['college_id', '=', \DB::raw('"'.$request->college_name.'"')],['program_id', '!=',  \DB::raw('"'.$request->id.'"')]])->get();
		if($data->count() == 0)
		{
			$arr = array('college_id'  => \DB::raw('"'.$request->college_name.'"'),'program_name'  => \DB::raw('"'.$request->name.'"'),'program_name_en'  => \DB::raw('"'.$request->name_en.'"'),'program_type'  => \DB::raw('"'.$request->program_type.'"'));
			\DB::table('academic_programs')->where('program_id', \DB::raw('"'.$request->id.'"'))->update($arr);
			return "success";
		}
		else
		return "error";
	}

	public function destroy(Request $request,$id)
	{
		\DB::table('academic_programs')->where('program_id', \DB::raw('"'.$request->id.'"'))->delete();
		return redirect()->route('academic_programs_index');
	}
}
