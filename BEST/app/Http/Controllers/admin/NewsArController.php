<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Image;

class NewsArController extends Controller
{
	public function index(Request $request)
	{
		$data =  array();
		$data['data'] = \DB::table('news')->join('academic_programs', 'news.program_id', '=', 'academic_programs.program_id')->where([['news_lang', '=',\DB::raw('"1"')]])->get();
		$data['colleges'] = \DB::table('colleges')->select('college_id','college_name')->get();
		return view('admin.news',compact("data"));
	}
	
	public function store(Request $request)
	{
		$user_id = \DB::table('users')->select('id')->where([['email', '=',\DB::raw('"'.Session::get('email').'"')]])->get()[0]->id;
		$data = \DB::table('news')->where([['news_title', '=',\DB::raw('"'.$request->title.'"')]])->get();
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
						
			\DB::table('news')->insert(array('program_id'  => \DB::raw('"'.$request->program_name.'"'),'news_lang'  => \DB::raw('"1"'),
					'news_title'  => \DB::raw('"'.$request->title.'"'),'news_det_portion'  => \DB::raw('"'.$request->portion.'"'),
					'news_det'  => \DB::raw('"'.$request->det_code.'"'),'news_date'  => \DB::raw('"'.$request->date.'"'),
					'keywords'  => \DB::raw('"'.$request->keywords.'"'),'posted_by_user'  => \DB::raw('"'.$user_id.'"'),
					'news_img' =>   \DB::raw('"'.$img_path.'"'),'news_file' =>   \DB::raw('"'.$file_path.'"')));
			return "success";
		}
		else
		return "error";
	}
	
	public function show(Request $request,$id)
	{
		$edit_data = \DB::table('news')->join('academic_programs', 'news.program_id', '=', 'academic_programs.program_id')->whereColumn([['news_id', '=', \DB::raw('"'.$id.'"')]])->first();
		if($edit_data != '')
		{
			$data =  array();
			$data['edit_data'] =  $edit_data;
			$data['data'] = \DB::table('news')->join('academic_programs', 'news.program_id', '=', 'academic_programs.program_id')->where([['news_lang', '=',\DB::raw('"1"')]])->get();
			$data['colleges'] = \DB::table('colleges')->select('college_id','college_name')->get();
			return view('admin.news',compact("data"));
		}
		else
		return redirect()->route('news_index');
	}
	
	public function update(Request $request)
	{
		$arr = array('program_id'  => \DB::raw('"'.$request->program_name.'"'),'news_title'  => \DB::raw('"'.$request->title.'"'),
					'news_det_portion'  => \DB::raw('"'.$request->portion.'"'),'news_det'  => \DB::raw('"'.$request->det_code.'"'),
					'news_date'  => \DB::raw('"'.$request->date.'"'),'keywords'  => \DB::raw('"'.$request->keywords.'"'));
		$data = \DB::table('news')->where([['news_title', '=',\DB::raw('"'.$request->title.'"')],['news_id', '!=',  \DB::raw('"'.$request->id.'"')]])->get();
		if($data->count() ==0)
		{
			$docs = \DB::table('news')->select('news_img','news_file')->whereColumn([['news_id', '=', \DB::raw('"'.$request->id.'"')]])->get();
			if($request->hasFile('img')){
				if($request->file('img')->isValid()) {
					try {
						$file = $request->file('img');
						$filename = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
						$img_path = $file->storeAs('images', $filename);
						if($docs[0]->news_img != ''){ Storage::delete($docs[0]->news_img); }
						$arr['news_img'] = \DB::raw('"'.$img_path.'"');
					} catch (Illuminate\Filesystem\FileNotFoundException $e){}
				}
			}
			if($request->hasFile('file')){
				if($request->file('file')->isValid()) {
					try {
						$file = $request->file('file');
						$filename = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
						$img_path = $file->storeAs('files', $filename);
						if($docs[0]->news_file != ''){ Storage::delete($docs[0]->news_file); }
						$arr['news_file'] = \DB::raw('"'.$img_path.'"');
					} catch (Illuminate\Filesystem\FileNotFoundException $e){}
				}
			}
			\DB::table('news')->where('news_id', \DB::raw('"'.$request->id.'"'))->update($arr);
			return "success";
		}
		else
		return 'error';
	}
	
	public function destroy(Request $request,$id)
	{
		$docs = \DB::table('news')->select('news_img','news_file')->whereColumn([['news_id', '=', \DB::raw('"'. $request->id.'"')]])->get();
		if(count($docs) != 0)
		{
			if($docs[0]->news_img != ''){ Storage::delete($docs[0]->news_img); }
			if($docs[0]->news_file != ''){ Storage::delete($docs[0]->news_file); }
			\DB::table('news')->where('news_id', \DB::raw('"'.$request->id.'"'))->delete();
		}
		return redirect()->route('news_index');
	}
	
	public function priority(Request $request,$id,$val){
		\DB::table('news')->update(array('news_priority'  => \DB::raw('"0"')));
		\DB::table('news')->where('news_id', \DB::raw('"'.$request->id.'"'))->update(array('news_priority'  => \DB::raw('"'.$val.'"')));
		$lang = \DB::table('news')->where('news_id', \DB::raw('"'.$request->id.'"'))->get()[0]->news_lang;
		if($lang == 1) { return redirect()->route('news_index'); } else { return redirect()->route('news_en_index'); }
	}
	
	public function approve(Request $request,$id,$val){
		\DB::table('news')->where('news_id', \DB::raw('"'.$request->id.'"'))->update(array('news_flag'  => \DB::raw('"'.$val.'"')));
		$lang = \DB::table('news')->where('news_id', \DB::raw('"'.$request->id.'"'))->get()[0]->news_lang;
		if($lang == 1) { return redirect()->route('news_index'); } else { return redirect()->route('news_en_index'); }
	}
}
