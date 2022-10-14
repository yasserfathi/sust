<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Storage;
use Image;

class AdsController extends Controller
{
	public function index(Request $request)
	{
		$data =  array();
		$data['data'] = \DB::table('ads')->where([['ads_lang', '=',\DB::raw('"1"')]])->get();
		$data['colleges'] = \DB::table('colleges')->select('college_id','college_name')->get();
		return view('admin.ads',compact("data"));
	}

	public function store(Request $request)
	{
		$user_id = \DB::table('users')->select('id')->where([['email', '=',\DB::raw('"'.Session::get('email').'"')]])->get()[0]->id;
		$data = \DB::table('ads')->where([['ads_title', '=',\DB::raw('"'.$request->title.'"')]])->get();
		if($data->count() ==0)
		{
			$img_path = $file_path = '';
			if($request->hasFile('img')) {
				if($request->file('img')->isValid()) {
					try {
						$file = $request->file('img');
						$filename = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
						$filename_thumb = rand(11111, 99999) . '_thumb.' . $file->getClientOriginalExtension();
						$img_path = $file->storeAs('images', $filename);
						$img = Image::make($file)->resize(300, 200)->save('storage/app/images/'.$filename_thumb);
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

			\DB::table('ads')->insert(array('college_id'  => \DB::raw('"'.$request->college_name.'"'),'ads_lang'  => \DB::raw('"1"'),
                    'ads_title'  => \DB::raw('"'.$request->title.'"'),'ads_det'  => \DB::raw('"'.$request->det_code.'"'),
                     'ads_date'  => \DB::raw('"'.date("Y/m/d").'"'),'ads_duration'  => \DB::raw('"'.$request->duration.'"'),
					'keywords'  => \DB::raw('"'.$request->keywords.'"'),'posted_by_user'  => \DB::raw('"'.$user_id.'"'),
					'ads_img' =>   \DB::raw('"'.$img_path.'"'),'ads_file' =>   \DB::raw('"'.$file_path.'"')));
			return "success";
		}
		else
		return "error";
	}

	public function show(Request $request,$id)
	{
		$edit_data = \DB::table('ads')->whereColumn([['ads_id', '=', \DB::raw('"'.$id.'"')]])->first();
		if($edit_data != '')
		{
			$data =  array();
			$data['edit_data'] =  $edit_data;
			$data['data'] = \DB::table('ads')->where([['ads_lang', '=',\DB::raw('"1"')]])->get();
			$data['colleges'] = \DB::table('colleges')->select('college_id','college_name')->get();
			return view('admin.ads',compact("data"));
		}
		else
		return redirect()->route('ads_index');
	}

	public function update(Request $request)
	{
		$arr = array('college_id'  => \DB::raw('"'.$request->college_name.'"'),'ads_title'  => \DB::raw('"'.$request->title.'"'),
					'ads_det'  => \DB::raw('"'.$request->det_code.'"'),
					'ads_duration'  => \DB::raw('"'.$request->duration.'"'),'keywords'  => \DB::raw('"'.$request->keywords.'"'));
		$data = \DB::table('ads')->where([['ads_title', '=',\DB::raw('"'.$request->title.'"')],['ads_id', '!=',  \DB::raw('"'.$request->id.'"')]])->get();
		if($data->count() ==0)
		{
			$docs = \DB::table('ads')->select('ads_img','ads_file')->whereColumn([['ads_id', '=', \DB::raw('"'.$request->id.'"')]])->get();
			if($request->hasFile('img')){
				if($request->file('img')->isValid()) {
					try {
						$file = $request->file('img');
						$filename = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
						$img_path = $file->storeAs('images', $filename);
						if($docs[0]->ads_img != ''){ Storage::delete($docs[0]->ads_img); }
						$arr['ads_img'] = \DB::raw('"'.$img_path.'"');
					} catch (Illuminate\Filesystem\FileNotFoundException $e){}
				}
			}
			if($request->hasFile('file')){
				if($request->file('file')->isValid()) {
					try {
						$file = $request->file('file');
						$filename = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
						$img_path = $file->storeAs('files', $filename);
						if($docs[0]->ads_file != ''){ Storage::delete($docs[0]->ads_file); }
						$arr['ads_file'] = \DB::raw('"'.$img_path.'"');
					} catch (Illuminate\Filesystem\FileNotFoundException $e){}
				}
			}
			\DB::table('ads')->where('ads_id', \DB::raw('"'.$request->id.'"'))->update($arr);
			return "success";
		}
		else
		return 'error';
	}

	public function destroy(Request $request,$id)
	{
		$docs = \DB::table('ads')->select('ads_img','ads_file')->whereColumn([['ads_id', '=', \DB::raw('"'. $request->id.'"')]])->get();
		if(count($docs) != 0)
		{
			if($docs[0]->ads_img != ''){ Storage::delete($docs[0]->ads_img); }
			if($docs[0]->ads_file != ''){ Storage::delete($docs[0]->ads_file); }
			\DB::table('ads')->where('ads_id', \DB::raw('"'.$request->id.'"'))->delete();
		}
		return redirect()->route('ads_index');
	}
}
