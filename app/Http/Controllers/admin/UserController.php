<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use App\Models\College;
use App\Models\StaffEmploy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class UserController extends Controller
{
    public function index()
    {
        $data =  array();
		$data = User::select("*")->with([
				'staff' => function ($query){
					$query->select('*')->with(['department' => function ($query2){
						$query2->select('*')->with(['college' => function ($query3){
							$query3->select('*');
						}])->get()->toArray();
					},
				])->get()->toArray();
			},
		])->get();
        $colleges = College::select('id','name')->get();
		return view('admin.users')->with('data', $data)->with('colleges',$colleges);
    }

    public function store(Request $request)
	{
        $data = StaffEmploy::with('department')->with('college')->select('id')->whereColumn([['department_id', '=', DB::raw('"'.$request->department_id.'"')],
                ['name', '=', DB::raw('"'.$request->name.'"')],['name_en', '=', DB::raw('"'.$request->name_en.'"')]])->get();
		if($data->count() == 0)
		{
            $active = 0;
			if(isset($request->active) && $request->active ==1) {
				$active = 1;
			}
			$user_validator = Validator::make($request->all(), [
				'name' => 'required|string',
				'name_en' => 'required|string',
                'email' => 'required|string',
                'phone' => 'required|string',
                'img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
			]);
            
            $image = $request->file('img');
            $imageName = time().'.'.$image->extension();
           
            $destinationPathThumbnail = public_path('/images/thumbnail');
            $img = Image::make($image->path());
            $img->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPathThumbnail.'/'.$imageName);
         
            // $destinationPath = public_path('/images/staff');
            // $image->move($destinationPath, $imageName);

            User::create(array_merge($user_validator->validated(),
				// ['user_id' => Auth::user()->id],
				['active' => $active],
                ['password' => bcrypt('admin')],
				['img' => $destinationPathThumbnail.'/'.$imageName],
			));

            $staff_employ_validator = Validator::make($request->all(), [
				'department_id' => 'required|string',
				'job_title' => 'required|string',
                'job_title_en' => 'required|string',
                'rank' => 'required|string',
                'hire_date' => 'required|string',
                'specialty' => 'required|string',
                'subspecialty' => 'required|string',
                'specialty_en' => 'required|string',
                'subspecialty_en' => 'required|string',
			]);

			StaffEmploy::create(array_merge($staff_employ_validator->validated(),
				['user_id' => Auth::user()->id],
			));
			return "success";
		}
		else
		return "error";
	}

    public function show(User $user)
    {
		$edit_data = $user::find($user->id)->select("*")->with([
			'staff' => function ($query){
				$query->select('*')->with(['department' => function ($query2){
					$query2->select('*')->with(['college' => function ($query3){
						$query3->select('*');
					}])->get()->toArray();
				},
				])->get()->toArray();
			},
		])->get();
		if($edit_data != '')
		{
            $data = User::select("*")->with([
				'staff' => function ($query){
					$query->select('*')->with(['department' => function ($query2){
						$query2->select('*')->with(['college' => function ($query3){
							$query3->select('*');
						}])->get()->toArray();
					},
					])->get()->toArray();
				},
			])->get();
		    $colleges = College::select('id','name')->get();
		    return view('admin.users')->with('data', $data)->with('colleges',$colleges)->with('edit_data',$edit_data);
		}
		else
		return redirect()->route('department.index');
    }

    public function update(Request $request, User $user)
    {
		$data = User::join('staff_employs', 'users.id', '=', 'staff_employs.user_id')->whereColumn([['name', '=', DB::raw('"'.$request->name.'"')],
			['name_en', '=', DB::raw('"'.$request->name_en.'"')],['phone', '=', DB::raw('"'.$request->phone.'"')],['email', '=', DB::raw('"'.$request->email.'"')],
			['job_title', '=', DB::raw('"'.$request->job_title.'"')],['job_title_en', '=', DB::raw('"'.$request->job_title_en.'"')],
			['rank', '=', DB::raw('"'.$request->hire_date.'"')],['rank', '=', DB::raw('"'.$request->hire_date.'"')],])->get();
			;
		if($data->count() == 0)
		{
			$active = 0;
			if(isset($request->active) && $request->active ==1) {
				$active = 1;
			}

			$user = $user::find($user->id)->select("*")->with([
				'staff' => function ($query){
					$query->select('*')->with(['department' => function ($query2){
						$query2->select('*')->with(['college' => function ($query3){
							$query3->select('*');
						}])->get()->toArray();
					},
					])->get()->toArray();
				},
			])->get();

			$user = User::find($request->id);
			$img_db = $user->get()[0]->img;

			if($request->hasFile('img')){
				if($request->file('img')->isValid()) {
					try {
						$image = $request->file('img');
						$imageName = time().'.'.$image->extension();
						$destinationPathThumbnail = public_path('/images/thumbnail');
						$img = Image::make($image->path());
						$img->resize(300, 300, function ($constraint) {
							$constraint->aspectRatio();
						})->save($destinationPathThumbnail.'/'.$imageName);
						$img_db = 'images/thumbnail/'.$imageName;
						if($user->get()[0]->img != ''){ Storage::delete(public_path('/images/thumbnail/'.$user->get()[0]->img)); }
					} catch (FileNotFoundException $e){}
				}
			}

			$user->name = $request->name;
			$user->name_en = $request->name_en;
			$user->email = $request->email;
			$user->phone = $request->phone;
			$user->active = $active;
			$user->img = $img_db;
			// +++++++++++++++++++ //
			$staff = StaffEmploy::find($request->department_id);
			$staff->id = $request->department_id;
			$staff->job_title = $request->job_title;
			$staff->job_title_en = $request->job_title_en;
			$staff->rank = $request->rank;
			$staff->hire_date = $request->hire_date;
			$staff->specialty = $request->specialty;
			$staff->subspecialty = $request->subspecialty;
			$staff->specialty_en = $request->specialty_en;
			$staff->subspecialty_en = $request->subspecialty_en;
			$staff->user_id = Auth::user()->id;
			$user->save();
			$staff->save();
			return "success";
		}
		else
		return "error";
    }

    public function destroy(User $user)
    {
		StaffEmploy::firstWhere('user_id', $user->id)->delete();
		$user::find($user->id)->delete();
		return redirect()->route('user.index');
    }

	public function list(Request $request)
    {
        $str = '';
        if ($request->id != '') {
			$data = StaffEmploy::join('users', 'staff_employs.id', '=', 'users.id')
					->where('department_id',$request->id)->get(['staff_employs.id', 'users.name']);
            $str = '<option value="">اختر العضو</option>';
            foreach ($data as $key) {
                $str .= '<option value="' . $key->id . '">' . $key->name . '</option>';
            }
        }
        return $str;
    }
}
