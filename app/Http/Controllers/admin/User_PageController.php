<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Storage;

class User_PageController extends Controller
{

    public function index(Request $request)
    {
        $data =  array();
        /*$data['pages'] = \DB::table('user_page')
            ->join('users', 'user_page.user_id','=', 'users.id')
            ->join(' academic_programs', 'users.program_id','=', ' academic_programs.program_id')
            ->join('colleges', ' academic_programs.college_id','=', 'colleges.college_id')->get();*/
        $data['pages'] = \DB::table('user_page')
            ->join('users', 'user_page.user_id', '=', 'users.id')
            ->join('category_page', 'user_page.pages', '=', 'category_page.id')
            ->join('categories', 'categories.category_id', '=', 'category_page.category_id')
            ->join('academic_programs', 'users.program_id', '=', 'academic_programs.program_id')
            ->join('colleges', 'academic_programs.college_id', '=', 'colleges.college_id')
            ->select('user_page.id as id', 'user_id', 'pages', 'fullname', 'users.program_id', 'name', 'category_name', 'program_name', 'college_name')->get();
        //print_r($data['pages']);
        $data['colleges'] = \DB::table('colleges')->get();
        $data['cats'] = \DB::table('categories')->get();
        $data['categories'] = \DB::table('categories')->get();
        $data['category_page'] = \DB::table('category_page')->get();
        return view('admin.user_page', compact("data"));
    }

    public function store(Request $request)
    {
        $data = \DB::table('user_page')
            ->join('category_page', 'category_page.id', '=', 'pages')
            ->join('categories', 'category_page.category_id', '=', 'categories.category_id')
            ->whereColumn([['pages', '=', \DB::raw('"' . $request->pages . '"')], ['user_id', '=', \DB::raw('"' . $request->user_name . '"')]])->get();
        if ($data->count() == 0) {
            \DB::table('user_page')->insert(array('user_id'  => \DB::raw('"' . $request->user_name . '"'), 'pages'  => \DB::raw('"' . $request->pages . '"')));
            return "success";
        } else
            return "error";
    }

    public function show(Request $request, $id)
    {
        $edit_data = \DB::table('user_page')
            ->join('users', 'user_page.user_id', '=', 'users.id')
            ->join('category_page', 'user_page.pages', '=', 'category_page.id')
            ->join('categories', 'categories.category_id', '=', 'category_page.category_id')
            ->join('academic_programs', 'users.program_id', '=', 'academic_programs.program_id')
            ->join('colleges', 'academic_programs.college_id', '=', 'colleges.college_id')
            ->select('colleges.college_id as college_id', 'categories.category_id', 'user_page.id as id', 'user_id', 'pages', 'fullname', 'users.program_id', 'name', 'category_name', 'program_name', 'college_name')
            ->whereColumn([['user_page.id', '=', \DB::raw('"' . $id . '"')]])->get();
        if ($edit_data != '') {
            $data =  array();
            $data['pages'] = \DB::table('user_page')
                ->join('users', 'user_page.user_id', '=', 'users.id')
                ->join('category_page', 'user_page.pages', '=', 'category_page.id')
                ->join('categories', 'categories.category_id', '=', 'category_page.category_id')
                ->join('academic_programs', 'users.program_id', '=', 'academic_programs.program_id')
                ->join('colleges', 'academic_programs.college_id', '=', 'colleges.college_id')
                ->select('user_page.id as id', 'user_id', 'pages', 'category_page.category_id','fullname', 'users.program_id', 'name', 'category_name', 'program_name', 'college_name')->get();
            $data['edit_data'] =  $edit_data;
            $data['colleges'] = \DB::table('colleges')->get();
            $data['cats'] = \DB::table('categories')->get();
			$data['edit_category_page'] = \DB::table('category_page')->where('category_id', \DB::raw('"' . $edit_data[0]->category_id . '"'))->get();
			$data['category_page'] = \DB::table('category_page')->get();
            $data['cat_page'] = \DB::table('category_page')->where('category_id', \DB::raw('"'.$data['pages'][0]->category_id.'"'))->get();
            //echo($data['pages'][0]->category_id);
            //print_r($data['edit_category_page']);
			return view('admin.user_page', compact("data"));
        } else
            return redirect()->route('user_page_index');
    }

    public function update(Request $request)
    {
        if(strlen($request->pages) != 0)
        {
            \DB::table('user_page')->where('id', \DB::raw('"' . $request->id . '"'))->update(array('pages' => \DB::raw('"'.$request->pages.'"')));
            return "success";
        }
        else
        {
            \DB::table('user_page')->where('id', \DB::raw('"' . $request->id . '"'))->delete();
            return "success";
        }
    }

    public function destroy(Request $request, $id)
    {
        \DB::table('user_page')->where('id', \DB::raw('"' . $request->id . '"'))->delete();
        return redirect()->route('user_page_index');
    }

    public function departs(Request $request)
    {
        $str = '';
        if ($request->id != '') {
            $data = \DB::table('academic_programs')->select('program_id', 'program_name')->where([['college_id', '=', \DB::raw('"' . $request->id . '"')]])->get();
            $str = '<option value="">اختر البرنامج</option>';
            foreach ($data as $key) {
                $str .= '<option value="' . $key->program_id . '">' . $key->program_name . '</option>';
            }
        }
        return $str;
    }

    public function users(Request $request)
    {
        $str = '';
        if ($request->id != '') {
            $data = \DB::table('users')->where([['program_id', '=', \DB::raw('"' . $request->id . '"')]])->get();
            $str = '<option value="">اختر المستخدم</option>';
            foreach ($data as $key) {
                $str .= '<option value="' . $key->id . '">' . $key->fullname . '</option>';
            }
        }
        return $str;
    }

    public function cat_page(Request $request)
    {
        $str = '';
        if ($request->id != '') {
            $data = \DB::table('categories')->join('category_page', 'categories.category_id', '=', 'category_page.category_id')->where([['categories.category_id', '=', \DB::raw('"' . $request->id . '"')]])->get();
			//$list_pages = \DB::table('user_page')->where('user_id', \DB::raw('"'. $request->user_name .'"'))->get();
			$list_pages = \DB::table('user_page')
				  ->join('category_page', 'category_page.id', '=', 'pages')
				  ->join('categories', 'category_page.category_id', '=', 'categories.category_id')
				  ->whereColumn([['category_page.category_id', '=', \DB::raw('"' . $request->id . '"')]])->get();
			if(count($list_pages) != 0){
				$pages = explode(',', $list_pages[0]->pages);
			}
			//print_r($list_pages);
			
			$str = '<div class="form-group pull-right" style="margin-bottom:10px" id="optionsarea"><label style="margin-bottom:10px">الصفحات :</label>';
			foreach ($data as $key) {
				$str .= '<div class="col-md-6 pull-right" style="padding:15px 0">
				<span style="float:right">' . $key->name . '</span>
							<div class="checkbox" style="margin: 1px 5px 0 0;float: right">';
				$str .= '<input class="enbl" id="' . $key->id . '" type="checkbox" ';
				if(count($list_pages) != 0 && in_array($key->id,$pages)) { $str.= 'checked'; }
				//foreach($pages as $page ){ if($key->id == $page){ $str.= 'checked'; }}
				$str.= ' >
				<span></span>
				</div></div>';
			}
			$str .= '</div>';
			/*$list_pages = \DB::table('user_page')->where('user_id', \DB::raw('"'. $request->user_name .'"'))->get();
			if(count($list_pages) != 0)
			{
				$pages = explode(',', \DB::table('user_page')->where('user_id', \DB::raw('"'. $request->user_name .'"'))->get()[0]->pages);
				$str = '<div class="form-group" style="margin-bottom:10px;height:65px" id="optionsarea"><label style="margin-bottom:10px">الصفحات :</label>';
				foreach ($data as $key) {
					$str .= '<div class="col-md-6 pull-right" style="height:60px">
					<span style="float:right">' . $key->name . '</span>
								<div class="checkbox" style="margin: 1px 50px 0 0;float: right">';
					$str .= '<input class="enbl" id="' . $key->id . '" type="checkbox" ';
					if(in_array($key->id,$pages)) { $str.= 'checked'; }
					$str.= ' >
					<span></span>
					</div></div>';
				}
				$str .= '</div>';
			}*/
        }
        return $str;
    }
}
