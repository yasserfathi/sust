<?php

namespace App\Http\Controllers\admin;

use App\Models\College;
use Illuminate\Http\Request;
use App\Models\StaffAcademic;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Filesystem\FileNotFoundException;

class StaffAcademicController extends Controller
{
    
    protected $page;

    public function __construct(Request $request)
    {
		$this->page = $request->segment(3);
    }
    
    public function index()
    {
		// $data = StaffAcademic::select('id','item','item_val','url','img','file','user_id')->with('user')->get();

        $data = DB::table('staff_academics')
            ->join('users', 'staff_academics.user_id', '=', 'users.id')
            ->join('staff_employs', 'staff_employs.user_id', '=', 'users.id')
            ->join('departments', 'staff_employs.department_id', '=', 'departments.id')
            ->join('colleges', 'departments.college_id', '=', 'colleges.id')
            ->select('staff_academics.id as id','users.name as name','rank','keywords','item_val','url','staff_academics.img as img','staff_academics.file as file')
            ->where('users.active',1)->get();
		$colleges = College::select('id','name')->get();
		return view('admin.staff_academic')->with('data', $data)->with('colleges',$colleges);

        //select u.name as name,rank,item_val,url,s.img as img,s.file as file FROM colleges c,departments d,staff_academics s,users u,staff_employs e WHERE s.user_id = u.id and e.department_id = d.id and d.college_id=c.id and u.active = 1;

        // $data = \DB::table('staff-academic')->whereColumn([['item', '=', \DB::raw('"'.config('page').'"')]])->get();
		// if($request->session()->has('staff_id')) {
		// 	$data = \DB::table('staff-academic')->whereColumn([['item', '=', \DB::raw('"'.config('page').'"')],['staff_id', '=', \DB::raw('"'.Session::get("staff_id").'"')]])->get();
		// }
    }

    public function store(Request $request)
    {
        $data = StaffAcademic::select('item_val')
            ->whereColumn([['item', '=', DB::raw('"' . $this->page . '"')],['lang', '=', DB::raw('"' . $request->lang . '"')],
                           ['item_val', '=', DB::raw('"' . $request->item_val . '"')],['user_id', '=', DB::raw('"' . $request->user_id . '"')]])->get();
        if ($data->count() == 0) {
            $img_path = $url = '';
			if($request->hasFile('img')) {
				if($request->file('img')->isValid()) {
					try {
						$file = $request->file('img');
						$filename = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
						$img_path = $file->storeAs('images', $filename);
					} catch (FileNotFoundException $e){}
				}
			}
			
			if ($request->has('url')) {
				$url = $request->url;
			}
            $validator = Validator::make($request->all(), [
                'item' => 'required',
                'item_val' => 'required',
                'user_id' => 'required',
            ]);
            StaffAcademic::create(array_merge($validator->validated(),
                ['item' => $this->page],
                ['auth_id' => Auth::user()->id],
                ['img' => $img_path],
                ['url' => $url],
            ));
            return "success";
        } else {
            return "error";
        }
    }

    public function show(StaffAcademic $staffAcademic)
    {
        $edit_data = $staffAcademic::whereColumn([['id', '=', DB::raw('"'.$staffAcademic->id.'"')]])->first();
		if($edit_data != '')
		{
            $data = $staffAcademic::select('id','item','item_val','url','img','file','user_id')->with('user')->get();
		    $colleges = College::select('id','name')->get();
		    return view('admin.staff_academic')->with('edit_data',$edit_data)->with('data', $data)->with('colleges',$colleges);
		}
		else
		return redirect()->route($this->page.'.index');
    }

    public function edit(StaffAcademic $staffAcademic)
    {
        //
    }

    public function update(Request $request, StaffAcademic $staffAcademic)
    {
        //
    }

    public function destroy(StaffAcademic $staffAcademic)
    {
        //
    }
}
