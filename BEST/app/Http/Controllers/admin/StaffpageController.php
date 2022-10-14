<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Storage;

class StaffpageController extends Controller
{
	public $page;
	public function __construct(Request $request)
    {
		$this->middleware('verifysession');
		config(['page' => $request->segment(3)]);
    }

	public function index(Request $request)
	{
		$data = \DB::table('staff-academic')->whereColumn([['item', '=', \DB::raw('"'.config('page').'"')]])->get();
		if($request->session()->has('staff_id')) {
			$data = \DB::table('staff-academic')->whereColumn([['item', '=', \DB::raw('"'.config('page').'"')],['staff_id', '=', \DB::raw('"'.Session::get("staff_id").'"')]])->get();
		}
		return view('admin.'.config('page'))->with('data', $data);
	}

	public function store(Request $request)
	{
		$data = \DB::table('staff-academic')->select('itemval')->whereColumn([['item', '=', \DB::raw('"'.config('page').'"')],
						['staff_id', '=', \DB::raw('"'.$request->staff_name.'"')],['itemval', '=', \DB::raw('"'.$request->itemval.'"')],['lang', '=', \DB::raw('"'.$request->lang.'"')]])->first();
        if(count((array)$data) == 0)
		{
			$img_path = $url = '';
			if($request->hasFile('img')) {
				if($request->file('img')->isValid()) {
					try {
						$file = $request->file('img');
						$filename = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
						$img_path = $file->storeAs('images', $filename);
					} catch (Illuminate\Filesystem\FileNotFoundException $e){}
				}
			}
			
			if ($request->has('url')) {
				$url = \DB::raw('"'.$request->url.'"');
			}

			/*if($request->hasFile('file')) {
				if($request->file('file')->isValid()) {
					try {
						$file = $request->file('file');
						$filename = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
						$file_path = $file->storeAs('files', $filename);
					} catch (Illuminate\Filesystem\FileNotFoundException $e){}
				}
			}*/
			
			\DB::table('staff-academic')->insert(array('item'  => \DB::raw('"'.config('page').'"'),'lang' =>  \DB::raw('"'.$request->lang.'"'),'url' => $url,
					'staff_id' =>  \DB::raw('"'.$request->staff_name.'"'),'itemval' =>   \DB::raw('"'.$request->itemval.'"'),'img' =>   \DB::raw('"'.$img_path.'"')));
			return "success";
		}
		else
		return "error";
	}

	public function show(Request $request,$page,$id)
	{
		$edit_data = \DB::table('staff-academic')->whereColumn([['id', '=', \DB::raw('"'.$id.'"')]])->first();
		if($edit_data != '')
		{
			$data = \DB::table('staff-academic')->whereColumn([['item', '=', \DB::raw('"'.config('page').'"')]])->get();
			if($request->session()->has('staff_id')) {
			$data = \DB::table('staff-academic')->whereColumn([['item', '=', \DB::raw('"'.config('page').'"')],['staff_id', '=', \DB::raw('"'.Session::get("staff_id").'"')]])->get();
			}
			$edit_data = \DB::table('staff-academic')
                        ->join('staff', 'staff-academic.staff_id', '=', 'staff.id')
                        ->join('staff_employment', 'staff_employment.staff_id', '=', 'staff.id')
                        ->join('academic_programs', 'staff_employment.program_id', '=', 'academic_programs.program_id')
                        ->select('staff-academic.id as id','staff-academic.staff_id as staff_id','name','url','lang','staff-academic.img as img','program_type','academic_programs.program_id', 'program_name','itemval')
						->whereColumn([['staff-academic.id', '=', \DB::raw('"'.$id.'"')]])->first();
			return view('admin.'.config('page'))->with('data', $data)->with('edit_data',$edit_data);
		}
		else
		return redirect()->route('staffpage_index');
	}

	public function update(Request $request)
	{
		$arr = array('lang' =>  \DB::raw('"'.$request->lang.'"'),'staff_id' =>  \DB::raw('"'.$request->staff_name.'"'),'itemval' =>   \DB::raw('"'.$request->itemval.'"'));
		$docs = \DB::table('staff-academic')->select('img')->whereColumn([['id', '=', \DB::raw('"'.$request->id.'"')]])->get();
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
		
		if ($request->has('url')) {
			$arr['url'] = \DB::raw('"'.$request->url.'"');
		}
		/*if($request->hasFile('file')){
			if($request->file('file')->isValid()) {
				try {
					$file = $request->file('file');
					$filename = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
					$img_path = $file->storeAs('files', $filename);
					if($docs[0]->page_file != ''){ Storage::delete($docs[0]->page_file); }
					$arr['page_file'] = \DB::raw('"'.$img_path.'"');
				} catch (Illuminate\Filesystem\FileNotFoundException $e){}
			}
		}*/

		\DB::table('staff-academic')->where('id', \DB::raw('"'.$request->id.'"'))->update($arr);
		return "success";
	}

	public function destroy(Request $request,$page,$id)
	{
		\DB::table('staff-academic')->where('id', \DB::raw('"'.$request->id.'"'))->delete();
		return redirect()->route('page_index');
	}
}
