<?php

namespace App\Http\Controllers\admin;

use App\Models\College;
use App\Models\Gallery;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Filesystem\FileNotFoundException;

class GalleryController extends Controller
{
    public function index(Request $request)
	{
		// dd(Auth::user());
		$data =  array();
		$galleries = Gallery::get();
		$data = Department::with('college')->get();
		$colleges = College::select('id','name')->get();
		return view('admin.gallery')->with('galleries', $galleries)->with('data', $data)->with('colleges',$colleges);
	}
	
	public function store(Request $request)
    {
		dd($request->instance()->query('user_id'));
		$data = Gallery::with('college')->select('title')->whereColumn([['department_id', '=', DB::raw('"'.$request->department_name.'"')],
                ['title', '=', DB::raw('"'.$request->title.'"')]])->get();
        if($data->count() == 0)
		{
			Gallery::insert(array('department_id'  => DB::raw('"'.$request->department_name.'"'),'title'  => DB::raw('"'.$request->title.'"'),
			'user_id'  => DB::raw('"'.$request->instance()->query('user_id').'"')));
			return "success";
		}
		else
		return "error";
    }
	
	public function show(Gallery $gallery)
    {
        $edit_data = $gallery::with('department')->whereColumn([['id', '=', DB::raw('"'.$gallery->id.'"')]])->first();
		if($edit_data != '')
		{
			$galleries = Gallery::get();
			$data = Department::with('college')->get();
			$colleges = College::select('id','name')->get();
			return view('admin.gallery')->with('galleries', $galleries)->with('data', $data)->with('colleges',$colleges)->with('edit_data',$edit_data);
		}
		else
		return redirect()->route(' gallery.index');
    }

    public function update(Request $request, Gallery $gallery)
    {
        $data = $gallery::with('college')->select('name')->whereColumn([['department_id', '=', DB::raw('"'.$request->department_name.'"')],
                ['title', '=', DB::raw('"'.$request->title.'"')]])->get();
		if($data->count() == 0)
		{
			$arr = array('department_id'  => DB::raw('"'.$request->department_name.'"'),'title'  => DB::raw('"'.$request->title.'"'));
			$gallery::where('id', DB::raw('"'.$request->id.'"'))->update($arr);
			return "success";
		}
		else
		return "error";
    }

    public function destroy(Gallery $gallery)
    {
        $gallery::where('id', DB::raw('"'.$gallery->id.'"'))->delete();
		return redirect()->route('gallery.index');
    }

	
	public function choose(Request $request)
	{
		$output = '';
		if($request->img !='')
		{
			$path = $request->img;
			if($request->imgchk == 'true')
			{
				$checked = 1;
			}
			else
			{
				$checked = 0;
			}
			$arr = array('gallery_flag' => $checked);
			/*\DB::enableQueryLog();
			Gallery::where('gallery_img','like', '%'.$path.'%')->update($arr);
			$query = \DB::getQueryLog();
			print_r($query);*/
			Gallery::where('gallery_img','like', '%'.$path.'%')->update($arr);
 			//==================================================
			$result = Gallery::orderBy('gallery_flag', 'DESC')->get();
			$output ='<ul class="grid cs-style-3">';
					$index = 0;
					foreach($result as $key)
					{
						$img = $key->gallery_img;
						if($index == 0) { $output.= '<div class="row" style="direction:rtl">';}
							$output.= '<li class="col-md-4 img-thumbnail">
								<figure class="img-responsive">
									<img src="'.url('/storage/app/').'/'.$img.'" class="gallery-img" style="height:170px;width:100%" >
									<figcaption>
									<a href="'.url('/show/').'/'.$key->gallery_id.'" class="btn btn-sm btn-primary">تعديل <i class="fa fa-pencil"></i></a>
									<a href="#" data-href="'.url('/destroy/').'/'.$key->gallery_id.'" id="'.$key->gallery_id.'" data-toggle="modal" data-target="#confirm-delete" class="btn btn-sm btn-danger" style="right:50%">حذف <i class="fa fa-trash-o"></i></a>
									</figcaption>
								</figure>
								<label class="checkbox ios7-switch" style="padding:0;right:30%;top:10px">
									<input class="enbl" id="'.substr($img,7,-4).'" type="checkbox"';
									if($key->gallery_flag == '1') $output.= 'checked';
									$output.='>
									<span></span>
								</label>
							</li>';
							$index++;
							if($index == 3) {$output.= '</div>'; $index = 0; }
					}
			$output.='</ul>';
		}
		echo $output;
	}
	
}
