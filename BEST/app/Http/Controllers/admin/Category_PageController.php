<?php

namespace App\Http\Controllers\admin;

use Session;
use Storage;
use App\Models\CategoryPage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Category_PageController extends Controller
{
	
	public function index(Request $request)
	{
		$data =  array();
		$data = \DB::table('category_page')->get();
		$categories = \DB::table('categories')->get();
		return view('admin.category_page')->with('data', $data)->with('categories',$categories);
	}
	
	public function store(Request $request)
	{
		$data = CategoryPage::select('name')->whereColumn([['name', '=', \DB::raw('"'.$request->name.'"')]])->get();
		if($data->count() == 0)
		{
			$category_name = '0';
			if($request->enblval == 1 ) { $category_name = $request->category_name; }
			$arr = array('category_id'  => \DB::raw('"'.$category_name.'"'),'name'  => \DB::raw('"'.$request->name.'"'),'url'  => \DB::raw('"'.$request->url.'"'));
			\DB::table('category_page')->insert($arr);
			return "success";
		}
		else
		return "error";
	}
	
	public function show(Request $request,$id)
	{
		$edit_data = \DB::table('category_page')->whereColumn([['id', '=', \DB::raw('"'.$id.'"')]])->first();
		if($edit_data != '')
		{
			$data =  array();
			$edit_data =  $edit_data;
			$data = \DB::table('category_page')->get();
			$categories = \DB::table('categories')->get();
			return view('admin.category_page')->with('data', $data)->with('categories',$categories)->with('edit_data',$edit_data);
		}
		else
		return redirect()->route('category_page_index');
	}
	
	public function update(Request $request)
	{
		$data = \DB::table('category_page')->select('name')->whereColumn([['name', '=', \DB::raw('"'.$request->name.'"')],['id', '!=',  \DB::raw('"'.$request->id.'"')]])->get();
		if($data->count() == 0)
		{
			$category_name = '0';
			if($request->enblval == 1 ) { $category_name = $request->category_name; }
			$arr = array('category_id'  => \DB::raw('"'.$category_name.'"'),'name'  => \DB::raw('"'.$request->name.'"'),'url'  => \DB::raw('"'.$request->url.'"'));
			\DB::table('category_page')->where('id', \DB::raw('"'.$request->id.'"'))->update($arr);
			return "success";
		}
		else
		return "error";
	}
	
	public function destroy(Request $request,$id)
	{
		\DB::table('category_page')->where('id', \DB::raw('"'.$request->id.'"'))->delete();
		return redirect()->route('category_page_index');
	}
}
