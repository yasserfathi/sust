<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Storage;

class StaffController extends Controller
{
	public function index(Request $request)
	{
		$data =  array();
		$data['data'] = \DB::table('staff')->get();
		return view('admin.staff',compact("data"));
		/*$pages = \DB::table('users')
			   ->join('user_page', 'users.id', '=', 'user_page.user_id')->whereColumn([['email', '=', \DB::raw('"'.Session::get("email").'"')]])->select('pages')->get();
		dd($pages);*/
	}
	
	public function store(Request $request)
	{
		$data = \DB::table('staff')->where([['name', '=',\DB::raw('"'.$request->name.'"')]])->get();
		if($data->count() ==0)
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
						
			\DB::table('staff')->insert(array('name'  => \DB::raw('"'.$request->name.'"'),'name_en'  => \DB::raw('"'.$request->name_en.'"'),
					'phone'  => \DB::raw('"'.$request->phone.'"'),'email'  => \DB::raw('"'.$request->email.'"'),'img' =>   \DB::raw('"'.$img_path.'"')));
			return "success";
		}
		else
		return "error";
	}
	
	public function show(Request $request,$id)
	{
		$edit_data = \DB::table('staff')->whereColumn([['id', '=', \DB::raw('"'.$id.'"')]])->first();
		if($edit_data != '')
		{
			$data =  array();
			$data['edit_data'] =  $edit_data;
			$data['data'] = \DB::table('staff')->get();
			return view('admin.staff',compact("data"));
		}
		else
		return redirect()->route('staff_index');
	}
	
	public function update(Request $request)
	{
		$arr = array('name'  => \DB::raw('"'.$request->name.'"'),'name_en'  => \DB::raw('"'.$request->name_en.'"'),
					'phone'  => \DB::raw('"'.$request->phone.'"'),'email'  => \DB::raw('"'.$request->email.'"'));
		$data = \DB::table('staff')->where([['name', '=',\DB::raw('"'.$request->name.'"')],['id', '!=',  \DB::raw('"'.$request->id.'"')]])->get();
		if($data->count() ==0)
		{
			$docs = \DB::table('staff')->select('img','email')->whereColumn([['id', '=', \DB::raw('"'.$request->id.'"')]])->get();
			if($request->hasFile('img')){
				if($request->file('img')->isValid()) {
					try {
						$file = $request->file('img');
						$filename = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
						$img_path = $file->storeAs('images', $filename);
						if($docs[0]->img != ''){ Storage::delete($docs[0]->img); }
						$arr['img'] = \DB::raw('"'.$img_path.'"');
					} catch (Illuminate\Filesystem\FileNotFoundException $e){}
				}
			}
			$user_id = \DB::table('users')->whereColumn([['email', '=', \DB::raw('"'.$docs[0]->email.'"')]])->get();
			if($user_id->count() != 0){
				\DB::table('users')->where('id', \DB::raw('"'.$user_id[0]->id.'"'))->update(array('phone'  => \DB::raw('"'.$request->phone.'"'),'email'  => \DB::raw('"'.$request->email.'"'),'img'  => \DB::raw('"'.$img_path.'"')));
			}
			\DB::table('staff')->where('id', \DB::raw('"'.$request->id.'"'))->update($arr);
			return "success";
		}
		else
		return 'error';
	}
	
	public function destroy(Request $request,$id)
	{
		$docs = \DB::table('staff')->select('img')->whereColumn([['id', '=', \DB::raw('"'. $request->id.'"')]])->get();
		if(count($docs) != 0)
		{
			if($docs[0]->img != ''){ Storage::delete($docs[0]->img); }
			\DB::table('staff')->where('id', \DB::raw('"'.$request->id.'"'))->delete();
		}
		return redirect()->route('staff_index');
	}
}
