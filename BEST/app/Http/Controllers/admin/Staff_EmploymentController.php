<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Storage;

class Staff_EmploymentController extends Controller
{
	public function index(Request $request)
	{
		$data =  array();
        $data['colleges'] = \DB::table('colleges')->get();
        $data['staff'] = \DB::table('staff')->get();
        $data['data'] = \DB::table('staff_employment')
                        ->join('staff', 'staff_employment.staff_id', '=', 'staff.id')
                        ->join('academic_programs', 'staff_employment.program_id', '=', 'academic_programs.program_id')
                        ->select('staff_employment.id as id','name','img','employment_date','academic_programs.program_id', 'program_name')->get();
        return view('admin.staff_employment',compact("data"));
	}

	public function store(Request $request)
	{
		$data = \DB::table('staff_employment')->where([['staff_id', '=',\DB::raw('"'.$request->staff_name.'"')],['program_id', '=',\DB::raw('"'.$request->program_name.'"')],['employment_date', '=',\DB::raw('"'.$request->date.'"')]])->first();
		if(count((array)$data) == 0)
		{
			\DB::table('staff_employment')->insert(array('staff_id'  => \DB::raw('"'.$request->staff_name.'"'),'program_id'  => \DB::raw('"'.$request->program_name.'"'),
					'job_title'  => \DB::raw('"'.$request->job_title.'"'),'job_title_en'  => \DB::raw('"'.$request->job_title_en.'"'),'employment_date' =>   \DB::raw('"'.$request->date.'"')));
			return "success";
		}
		else
		return "error";
	}

	public function show(Request $request,$id)
	{
        $edit_data = \DB::table('staff_employment')
                        ->join('staff', 'staff_employment.staff_id', '=', 'staff.id')
                        ->join('academic_programs', 'staff_employment.program_id', '=', 'academic_programs.program_id')
                        ->whereColumn([['staff_employment.id', '=', \DB::raw('"'.$id.'"')]])->first();
		if($edit_data != '')
		{
			$data =  array();
            $data['edit_data'] =  $edit_data;
            $data['colleges'] = \DB::table('colleges')->get();
            $data['staff'] = \DB::table('staff')->get();
			$data['data'] = \DB::table('staff_employment')
                        ->join('staff', 'staff_employment.staff_id', '=', 'staff.id')
                        ->join('academic_programs', 'staff_employment.program_id', '=', 'academic_programs.program_id')
                        ->select('staff_employment.id as id','name','img','employment_date','academic_programs.program_id', 'program_name')->get();
			return view('admin.staff_employment',compact("data"));
		}
		else
		return redirect()->route('staff_employment_index');
	}

    public function update(Request $request)

	{
		$arr = array('staff_id'  => \DB::raw('"'.$request->staff_name.'"'),'program_id'  => \DB::raw('"'.$request->program_name.'"'),
        'job_title'  => \DB::raw('"'.$request->job_title.'"'),'job_title_en'  => \DB::raw('"'.$request->job_title_en.'"'),'employment_date' =>   \DB::raw('"'.$request->date.'"'));
        $data = \DB::table('staff_employment')->where([['staff_id', '=',\DB::raw('"'.$request->staff_name.'"')],
        ['program_id', '=',\DB::raw('"'.$request->program_name.'"')],['employment_date', '=',\DB::raw('"'.$request->date.'"')],['id', '!=',  \DB::raw('"'.$request->id.'"')]])->get();
		if($data->count() ==0)
		{
			\DB::table('staff_employment')->where('id', \DB::raw('"'.$request->id.'"'))->update($arr);
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

    public function programs(Request $request)
    {
        $str = '';
        if ($request->id != '') {
            $data = \DB::table('academic_programs')->where([['program_type', '=', \DB::raw('"' . $request->id . '"')]])->get();
            $str = '<option value="">اختر البرنامج</option>';
            foreach ($data as $key) {
                $str .= '<option value="' . $key->program_id . '">' . $key->program_name . '</option>';
            }
        }
        return $str;
    }
	
	public function programs_en(Request $request)
    {
        $str = '';
        if ($request->id != '') {
            $data = \DB::table('academic_programs')->where([['program_type', '=', \DB::raw('"' . $request->id . '"')]])->get();
            $str = '<option value="">Choose Program</option>';
            foreach ($data as $key) {
                $str .= '<option value="' . $key->program_id . '">' . $key->program_name_en . '</option>';
            }
        }
        return $str;
    }
	
	public function staff(Request $request)
    {
        $str = '';
        if ($request->id != '') {
            $data = \DB::table('staff_employment')->join('staff','staff_employment.staff_id', '=', 'staff.id')
					 ->join('academic_programs', 'staff_employment.program_id', '=', 'academic_programs.program_id')
					 ->where([['staff_employment.program_id', '=', \DB::raw('"' . $request->id . '"')]])
					  ->select('staff_employment.id as id','name','staff_id','academic_programs.program_id')->get();
			//dd($data->toSql());
		    $str = '<option value="">اختر الاسم</option>';
            foreach ($data as $key) {
                $str .= '<option value="' . $key->staff_id . '">' . $key->name . '</option>';
            }
        }
        return $str;
    }
	
	public function staff_email(Request $request)
    {
        if ($request->id != '') {
            return \DB::table('staff')->where('id', \DB::raw('"'.$request->id.'"'))->get()[0]->email;
        }
    }

}
