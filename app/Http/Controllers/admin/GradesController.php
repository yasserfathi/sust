<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Storage;

class GradesController extends Controller
{
	
	public function index(Request $request)
	{
		$data = \DB::table('grades')->get();
		return view('admin.grades')->with('data', $data);
	}
	
	public function store(Request $request)
	{
		$data = \DB::table('grades')->whereColumn([['grade_title', '=', \DB::raw('"'.$request->title.'"')]])->get();
		if($data->count() == 0)
		{
			\DB::table('grades')->insert(array('grade_title'  => \DB::raw('"'.$request->title.'"'),'grade_title_en'  => \DB::raw('"'.$request->title_en.'"')));
			return "success";
		}
		else
		return "error";
	}
	
	public function show(Request $request,$id)
	{
		$edit_data = \DB::table('grades')->whereColumn([['grade_id', '=', \DB::raw('"'.$id.'"')]])->first();
		if($edit_data != '')
		{
			$data = \DB::table('grades')->get();
			return view('admin.grades')->with('data', $data)->with('edit_data',$edit_data);
		}
		else
		return redirect()->route('grades_index');
	}
	
	public function update(Request $request)
	{
		$data = \DB::table('grades')->select('grade_title')->whereColumn([['grade_title', '=', \DB::raw('"'.$request->title.'"')],['grade_id', '!=',  \DB::raw('"'.$request->id.'"')]])->get();
		if($data->count() == 0)
		{
			$arr = array('grade_title'  => \DB::raw('"'.$request->title.'"'),'grade_title_en'  => \DB::raw('"'.$request->title_en.'"'));
			\DB::table('grades')->where('grade_id', \DB::raw('"'.$request->id.'"'))->update($arr);
			return "success";
		}
		else
		return "error";
	}
	
	public function destroy(Request $request,$id)
	{
		\DB::table('grades')->where('grade_id', \DB::raw('"'.$request->id.'"'))->delete();
		return redirect()->route('grades_index');
	}
}
