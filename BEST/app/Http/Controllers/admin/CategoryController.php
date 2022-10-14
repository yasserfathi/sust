<?php

namespace App\Http\Controllers\admin;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
	{
		$data = Category::get();
		return view('admin.categories')->with('data', $data);
	}
	
	public function store(Request $request)
	{
		$data = Category::select('title')->whereColumn([['title', '=', DB::raw('"'.$request->title.'"')]])->get();
		if($data->count() == 0)
		{
			$validator = Validator::make($request->all(), [
				'title' => 'required|string',
			]);
	
			Category::create(array_merge($validator->validated(),
				['user_id' => Auth::user()->id],
			));
			return "success";
		}
		else
		return "error";
	}
	
	public function show(Category $category)
	{
		$edit_data = $category::whereColumn([['id', '=', DB::raw('"'.$category->id.'"')]])->first();
		if($edit_data != '')
		{
			$data = $category::get();
			return view('admin.categories')->with('data', $data)->with('edit_data',$edit_data);
		}
		else
		return redirect()->route('category_index');
	}
	
	public function update(Request $request)
	{
		$data = Category::select('title')->whereColumn([['title', '=', DB::raw('"'.$request->title.'"')]])->get();
		if($data->count() == 0)
		{
			$category = Category::find($request->id);
			$category->title = $request->title;
			$category->user_id = Auth::user()->id;
			$category->save();
			return "success";
		}
		else
		return "error";
	}
	
	public function destroy(Category $category)
	{
		$category::find($category->id)->delete();
		return redirect()->route('category.index');
	}
}
