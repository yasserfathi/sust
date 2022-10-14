<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Storage;

class PasswordController extends Controller
{
	
	public function index(Request $request)
	{
		$data =  array();
		$data['data'] = \DB::table('users')->get();
		$data['colleges'] = \DB::table('colleges')->select('college_id','college_name')->get();
		return view('admin.password',compact("data"));
	}
	
	public function update(Request $request)
	{
		$key='abc~$&*@1234!';
		$password = md5($request->password.$key);
		$data = \DB::table('users')->select('email')->whereColumn([['email', '=', \DB::raw('"'.Session::get('email').'"')],['password', '=',  \DB::raw('"'.$password.'"')]])->get();
		if($data->count() == 0)
		{
			\DB::table('users')->where('email', \DB::raw('"'.Session::get('email').'"'))->update(array('password'  => \DB::raw('"'.$password.'"')));
			return "success";
		}
		else
		return "error";
	}
	
}
