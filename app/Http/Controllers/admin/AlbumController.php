<?php

namespace App\Http\Controllers\admin;

use App\Models\Album;
use App\Models\College;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AlbumController extends Controller
{
    public function index()
    {
        $data =  array();
		$data = Album::with('college')->get();
		$colleges = College::select('id','name')->get();
		return view('admin.albums')->with('data', $data)->with('colleges',$colleges);
    }

    public function store(Request $request)
	{
		$data = Album::with('college')->select('title')->whereColumn([['college_id', '=', DB::raw('"'.$request->college_id.'"')],
                ['title', '=', DB::raw('"'.$request->title.'"')],['title_en', '=', DB::raw('"'.$request->title_en.'"')],
				['keywords', '=', DB::raw('"'.$request->keywords.'"')],['active', '=', DB::raw('"'.$request->active.'"')]])->get();
		if($data->count() == 0)
		{
			$active = 0;
			if(isset($request->active) && $request->active ==1) {
				$active = 1;
			}
			$validator = Validator::make($request->all(), [
				'college_id' => 'required|string',
				'title' => 'required|string',
				'title_en' => 'required|string',
				'keywords' => 'required|string',
			]);

			Album::create(array_merge($validator->validated(),
				['user_id' => Auth::user()->id],
				['active' => $active],
			));
			return "success";
		}
		else
		return "error";
	}
	
    public function show(Album $album)
    {
        $edit_data = $album::with('college')->whereColumn([['id', '=', DB::raw('"'.$album->id.'"')]])->first();
		if($edit_data != '')
		{
            $data = $album::with('college')->get();
		    $colleges = College::select('id','name')->get();
		    return view('admin.albums')->with('data', $data)->with('colleges',$colleges)->with('edit_data',$edit_data);
		}
		else
		return redirect()->route('album.index');
    }

    public function update(Request $request, Album $album)
    {
        $data = $album::with('college')->select('title')->whereColumn([['college_id', '=', DB::raw('"'.$request->college_id.'"')],
                ['title', '=', DB::raw('"'.$request->title.'"')],['title_en', '=', DB::raw('"'.$request->title_en.'"')],
				['keywords', '=', DB::raw('"'.$request->keywords.'"')],['active', '=', DB::raw('"'.$request->active.'"')]])->get();
		if($data->count() == 0)
		{
			$active = 0;
			if(isset($request->active) && $request->active ==1) {
				$active = 1;
			}

			$album = $album::find($request->id);
			$album->college_id = $request->college_id;
			$album->title = $request->title;
			$album->title_en = $request->title_en;
			$album->keywords = $request->keywords;
			$album->active = $active;
			$album->user_id = Auth::user()->id;
			$album->save();
			return "success";
		}
		else
		return "error";
    }

	public function destroy(Album $album)
    {
		$album::find($album->id)->delete();
		return redirect()->route('album.index');
    }
	
	 public function list(Request $request)
    {
        $str = '';
        if ($request->id != '') {
            $data = Album::where([['college_id', '=', DB::raw('"' . $request->id . '"')]])->get();
            $str = '<option value="">اختر المعرض</option>';
            foreach ($data as $key) {
                $str .= '<option value="' . $key->id . '">' . $key->title . '</option>';
            }
        }
        return $str;
    }
}