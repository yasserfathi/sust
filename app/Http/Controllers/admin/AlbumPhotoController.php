<?php

namespace App\Http\Controllers\admin;

use App\Models\Album;
use App\Models\College;
use App\Models\AlbumPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Image;

  

class AlbumPhotoController extends Controller
{
    public function index()
    {
        $data =  array();
		$data = AlbumPhoto::with('album')->get();
		$colleges = College::select('id','name')->get();
		return view('admin.album-photos')->with('data', $data)->with('colleges',$colleges);
    }

    public function store(Request $request)
	{
		dd($request);;
        $data = AlbumPhoto::with('album')->select('title')->whereColumn([['album_id', '=', DB::raw('"'.$request->album_id.'"')],
                ['title', '=', DB::raw('"'.$request->title.'"')],['title_en', '=', DB::raw('"'.$request->title_en.'"')]])->get();
		if($data->count() == 0)
		{
			$img_path = '';
			if($request->hasFile('img')) {
				if($request->file('img')->isValid()) {
					try {
						$file = $request->file('img');
						$filename = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
						$filename_thumb = rand(11111, 99999) . '_thumb.' . $file->getClientOriginalExtension();
						$img_path = $file->storeAs('images', $filename);
						$img = Image::make($file)->resize(300, 200)->save(public_path('images/'.$filename_thumb));
					} catch (FileNotFoundException $e){}
				}
			}

            echo 'ABC';

            // $validator = Validator::make($request->all(), [
			// 	'college_id' => 'required|string',
			// 	'name' => 'required|string',
			// 	'name_en' => 'required|string',
			// ]);

			// Department::create(array_merge($validator->validated(),
			// 	['user_id' => Auth::user()->id],
			// 	['active' => $active],
			// ));
			// return "success";
		}
		else
		return "error";
	}

    public function show(AlbumPhoto $albumPhoto)
    {
        //
    }

    public function update(Request $request, AlbumPhoto $albumPhoto)
    {
        //
    }

    public function destroy(AlbumPhoto $albumPhoto)
    {
        //
    }
}
