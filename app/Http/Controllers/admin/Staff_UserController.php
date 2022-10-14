<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Storage;

class Staff_UserController extends Controller
{

	public function index(Request $request)
	{
		$data =  array();
		$data['data'] = \DB::table('users')->get();
		$data['colleges'] = \DB::table('colleges')->select('college_id','college_name')->get();
		return view('admin.staff_user',compact("data"));
	}

	public function store(Request $request)
	{
		$data = \DB::table('users')->whereColumn([['email', '=', \DB::raw('"'.$request->mail.'"')]])->get();
		if($data->count() == 0)
		{
			$key='abc~$&*@1234!';
			$staff = \DB::table('staff')->whereColumn([['email', '=', \DB::raw('"'.$request->mail.'"')]])->get()[0];
			$staff_pages = \DB::table('categories')->join('category_page', 'categories.category_id', '=', 'category_page.category_id')
						->where('categories.category_id', \DB::raw('"9"'))->select('id')->get();
			$i = 1;
			$str  = '';
			foreach($staff_pages as $pages){
				$str .= $pages->id;
				$i++;
				if($i <= count($staff_pages)){ $str.= ','; }
			}
			
			\DB::table('users')->insert(array('program_id'  => \DB::raw('"'.$request->program_name.'"'),'fullname'  => \DB::raw('"'.$staff->name.'"'),
			'password'  => \DB::raw('"'.md5($request->password.$key).'"'),'email'  => \DB::raw('"'.$request->mail.'"'),'phone'  => \DB::raw('"'.$staff->phone.'"'),'img'  => \DB::raw('"'.$staff->img.'"')));
			$user_id = \DB::table('users')->whereColumn([['email', '=', \DB::raw('"'.$request->mail.'"')]])->select('id')->get()[0]->id;
			\DB::table('user_page')->insert(array('user_id'  => \DB::raw('"'.$user_id.'"'),'pages' => \DB::raw('"'.$str.'"')));
			return "success";
		}
		else
		return "error";
	}

	public function show(Request $request,$id)
	{
		$edit_data = \DB::table('users')->whereColumn([['id', '=', \DB::raw('"'.$id.'"')]])->first();
		$edit_program = \DB::table('academic_programs')->whereColumn([['program_id', '=', \DB::raw('"'.$edit_data->program_id.'"')]])->first();
		$edit_data = \DB::table('users')->whereColumn([['id', '=', \DB::raw('"'.$id.'"')]])->first();
		if($edit_data != '')
		{
			$data =  array();
			$data['edit_data'] =  $edit_data;
			$data['edit_program'] =  $edit_program;
			$data['data'] = \DB::table('users')->get();
			$data['colleges'] = \DB::table('colleges')->join('academic_programs', 'academic_programs.college_id', '=', 'colleges.college_id')->get();
			return view('admin.users',compact("data"));
		}
		else
		return redirect()->route('users_index');
	}

	public function update(Request $request)
	{
        $data = \DB::table('users')->select('email')->whereColumn([['email', '=', \DB::raw('"'.$request->email.'"')],['id', '!=',  \DB::raw('"'.$request->id.'"')]])->get();
		if($data->count() == 0)
		{
			$key='abc~$&*@1234!';
			$program_name = 0;
			if($request->enblval == 0 ) { $program_name = $request->program_name; }
			$arr = array('program_id'  => \DB::raw('"'.$program_name.'"'),'fullname'  => \DB::raw('"'.$request->name.'"'),'password'  => \DB::raw('"'.$request->password.'"'),
			'password'  => \DB::raw('"'.md5($request->password.$key).'"'),'email'  => \DB::raw('"'.$request->email.'"'),'phone'  => \DB::raw('"'.$request->phone.'"'));
			$docs = \DB::table('users')->select('img')->whereColumn([['id', '=', \DB::raw('"'.$request->id.'"')]])->get();
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
			\DB::table('users')->where('id', \DB::raw('"'.$request->id.'"'))->update($arr);
			return "success";
		}
		else
		return "error";
	}

	public function destroy(Request $request,$id)
	{
		\DB::table('users')->where('id', \DB::raw('"'.$request->id.'"'))->delete();
		return redirect()->route('users_index');
	}
}
