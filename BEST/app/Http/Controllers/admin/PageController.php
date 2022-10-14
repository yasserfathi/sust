<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Storage;

class PageController extends Controller
{
	public $page;
	public function __construct(Request $request)
    {
		$this->middleware('verifysession');
		config(['page' => $request->segment(3)]);
    }

	public function index(Request $request)
	{
		$data = \DB::table('pages')->whereColumn([['page_title', '=', \DB::raw('"'.config('page').'"')]])->get();
		return view('admin.'.config('page'))->with('data', $data);
	}

	public function store(Request $request)
	{
		$data = \DB::table('pages')->select('page_id','page_lang')->whereColumn([['page_title', '=', \DB::raw('"'.config('page').'"')],
						['page_lang', '=', \DB::raw('"'.$request->lang.'"')]])->first();
        if(count((array)$data) == 0)
		{
			$img_path = $file_path = '';
			if($request->hasFile('img')) {
				if($request->file('img')->isValid()) {
					try {
						$file = $request->file('img');
						$filename = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
						$img_path = $file->storeAs('images', $filename);
					} catch (Illuminate\Filesystem\FileNotFoundException $e){}
				}
			}

			if($request->hasFile('file')) {
				if($request->file('file')->isValid()) {
					try {
						$file = $request->file('file');
						$filename = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
						$file_path = $file->storeAs('files', $filename);
					} catch (Illuminate\Filesystem\FileNotFoundException $e){}
				}
			}

			\DB::table('pages')->insert(array('page_title'  => \DB::raw('"'.config('page').'"'),'page_lang' =>  \DB::raw('"'.$request->lang.'"'),
					'page_det_portion' =>   \DB::raw('"'.$request->portion.'"'),'page_img' =>   \DB::raw('"'.$img_path.'"'),
					'page_file' =>   \DB::raw('"'.$file_path.'"'),'page_det' => \DB::raw('"'.htmlspecialchars($request->det_code).'"')));
			return "success";
		}
		else
		return "error";
	}

	public function show(Request $request,$page,$id)
	{
		$edit_data = \DB::table('pages')->whereColumn([['page_id', '=', \DB::raw('"'.$id.'"')]])->first();
		if($edit_data != '')
		{
			$data = \DB::table('pages')->select('page_id','page_lang')->whereColumn([['page_title', '=', \DB::raw('"'.config('page').'"')]])->get();
			return view('admin.'.config('page'))->with('data', $data)->with('edit_data',$edit_data);
		}
		else
		return redirect()->route('page_index');
	}

	public function update(Request $request)
	{
		$arr = array('page_det_portion' =>   \DB::raw('"'.$request->portion.'"'),'page_det' => \DB::raw('"'.htmlspecialchars($request->det_code).'"'));
		$docs = \DB::table('pages')->select('page_img','page_file')->whereColumn([['page_id', '=', \DB::raw('"'.$request->id.'"')]])->get();
		if($request->hasFile('img')){
			if($request->file('img')->isValid()) {
				try {
					$file = $request->file('img');
					$filename = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
					$img_path = $file->storeAs('images', $filename);
					if($docs[0]->page_img != ''){ Storage::delete($docs[0]->page_img); }
					$arr['page_img'] = \DB::raw('"'.$img_path.'"');
				} catch (Illuminate\Filesystem\FileNotFoundException $e){}
			}
		}
		if($request->hasFile('file')){
			if($request->file('file')->isValid()) {
				try {
					$file = $request->file('file');
					$filename = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
					$img_path = $file->storeAs('files', $filename);
					if($docs[0]->page_file != ''){ Storage::delete($docs[0]->page_file); }
					$arr['page_file'] = \DB::raw('"'.$img_path.'"');
				} catch (Illuminate\Filesystem\FileNotFoundException $e){}
			}
		}

		\DB::table('pages')->where('page_id', \DB::raw('"'.$request->id.'"'))->update($arr);
		return "success";
	}

	public function destroy(Request $request,$page,$id)
	{
		$docs = \DB::table('pages')->select('page_img','page_file')->whereColumn([['page_id', '=', \DB::raw('"'. $request->id.'"')]])->get();
		if(count($docs) != 0)
		{
			if($docs[0]->page_img != ''){ Storage::delete($docs[0]->page_img); }
			if($docs[0]->page_file != ''){ Storage::delete($docs[0]->page_file); }
			\DB::table('pages')->where('page_id', \DB::raw('"'.$request->id.'"'))->delete();
		}
		return redirect()->route('page_index');
	}
}
