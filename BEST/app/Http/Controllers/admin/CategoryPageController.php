<?php

namespace App\Http\Controllers\admin;

use App\Models\Category;
use App\Models\CategoryPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CategoryPageController extends Controller
{
    public function index()
    {
        $data =  array();
		$data = CategoryPage::with('category')->get();
		$categories = Category::select('id','title')->get();
		return view('admin.category_pages')->with('data', $data)->with('categories',$categories);
    }
	
	public function store(Request $request)
	{
		$data = CategoryPage::with('category')->select('title')->whereColumn([['category_id', '=', DB::raw('"'.$request->category_id.'"')],
                ['title', '=', DB::raw('"'.$request->title.'"')],['url', '=', DB::raw('"'.$request->url.'"')]])->get();
		if($data->count() == 0)
		{
			$validator = Validator::make($request->all(), [
				'category_id' => 'required|string',
				'title' => 'required|string',
                'url' => 'required|string',
			]);

			CategoryPage::create(array_merge($validator->validated(),
				['user_id' => Auth::user()->id],
			));
			return "success";
		}
		else
		return "error";
	}

    public function show(CategoryPage $categoryPage)
    {
        $edit_data = $categoryPage::with('category')->whereColumn([['id', '=', DB::raw('"'.$categoryPage->id.'"')]])->first();
		if($edit_data != '')
		{
            $data = $categoryPage::with('category')->get();
            $categories = Category::select('id','title')->get();
		    return view('admin.category_pages')->with('data', $data)->with('categories',$categories)->with('edit_data',$edit_data);
		}
		else
		return redirect()->route('category_pages.index');
    }

    public function update(Request $request, CategoryPage $categoryPage)
    {
        $data = $categoryPage::with('category')->select('title')->whereColumn([['category_id', '=', DB::raw('"'.$request->category_id.'"')],
                ['title', '=', DB::raw('"'.$request->title.'"')],['url', '=', DB::raw('"'.$request->url.'"')]])->get();
		if($data->count() == 0)
		{
			$categoryPage = $categoryPage::find($request->id);
			$categoryPage->category_id = $request->category_id;
			$categoryPage->title = $request->title;
			$categoryPage->url = $request->url;
			$categoryPage->user_id = Auth::user()->id;
			$categoryPage->save();
			return "success";
		}
		else
		return "error";
    }

    public function destroy(CategoryPage $categoryPage)
    {
		$categoryPage::find($categoryPage->id)->delete();
		return redirect()->route('department.index');
    }

	public function list(Request $request)
    {
        $str = '';
        if ($request->id != '') {
            $data = CategoryPage::where([['category_id', '=', DB::raw('"' . $request->id . '"')]])->get();
            $str = '<option value="">اختر الفئة</option>';
            foreach ($data as $key) {
                $str .= '<option value="' . $key->id . '">' . $key->title . '</option>';
            }
        }
        return $str;
    }
}