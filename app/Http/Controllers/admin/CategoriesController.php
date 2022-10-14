<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Storage;

class CategoriesController extends Controller
{
	
	public function index(Request $request)
	{
		$data = \DB::table('categories')->get();
		return view('admin.categories')->with('data', $data);
	}
	
	public function store(Request $request)
	{
		$data = \DB::table('categories')->whereColumn([['category_name', '=', \DB::raw('"'.$request->name.'"')]])->get();
		if($data->count() == 0)
		{
			\DB::table('categories')->insert(array('category_name'  => \DB::raw('"'.$request->name.'"')));
			return "success";
		}
		else
		return "error";
	}
	
	public function show(Request $request,$id)
	{
		$edit_data = \DB::table('categories')->whereColumn([['category_id', '=', \DB::raw('"'.$id.'"')]])->first();
		if($edit_data != '')
		{
			$data = \DB::table('categories')->get();
			return view('admin.categories')->with('data', $data)->with('edit_data',$edit_data);
		}
		else
		return redirect()->route('categories_index');
	}
	
	public function update(Request $request)
	{
		$data = \DB::table('categories')->select('category_name')->whereColumn([['category_name', '=', \DB::raw('"'.$request->name.'"')],['category_id', '!=', \DB::raw('"'.$request->id.'"')]])->get();
		if($data->count() == 0)
		{
			\DB::table('categories')->where('category_id', \DB::raw('"'.$request->id.'"'))->update(array('category_name'  => \DB::raw('"'.$request->name.'"')));
			return "success";
		}
		else
		return "error";
	}
	
	public function destroy(Request $request,$id)
	{
		\DB::table('categories')->where('category_id', \DB::raw('"'.$request->id.'"'))->delete();
		return redirect()->route('categories_index');
	}
}
