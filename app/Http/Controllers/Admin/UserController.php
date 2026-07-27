<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\User;
use App\Models\StaffEmploy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StaffEmployRequest;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Validate inputs
        $validated = $request->validate([
            'items' => 'sometimes|integer',
            'search' => 'sometimes|string|max:255',
            'orderby' => 'sometimes|string',
            'ascend' => 'sometimes|string|in:asc,desc'
        ]);

        $itemsPerPage = $validated['items'] ?? 15;
        $search = $validated['search'] ?? '';
        $orderBy = $validated['orderby'] ?? null;
        $sortDirection = $validated['ascend'] ?? 'asc';

        $query = User::query()
            ->with([
                'staff' => function ($query) {
                    $query->select('id', 'user_id', 'job_title', 'rank', 'department_id', 'hire_date');
                },
                'staff.department' => function ($query) {
                    $query->select('id', 'college_id', 'name', 'name_en');
                },
                'staff.department.college' => function ($query) {
                    $query->select('id', 'name', 'name_en');
                }
            ]);

        // Apply search conditions
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('staff', function ($q) use ($search) {
                    $q->where('job_title', 'like', "%{$search}%")
                        ->orWhere('rank', 'like', "%{$search}%");
                })
                    ->orWhereHas('staff.department', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('staff.department.college', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('name_en', 'like', "%{$search}%")
                    ->orWhere('univ_no', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Apply sorting
        if ($orderBy) {
            switch ($orderBy) {
                case 'name':
                    $query->orderBy('name', $sortDirection);
                    break;
                case 'staff.department.college.name':
                    $query->orderBy(
                        StaffEmploy::select('colleges.name')
                            ->join('departments', 'departments.id', '=', 'staff_employs.department_id')
                            ->join('colleges', 'colleges.id', '=', 'departments.college_id')
                            ->whereColumn('staff_employs.user_id', 'users.id')
                            ->latest('staff_employs.hire_date')
                            ->limit(1),
                        $sortDirection
                    );
                    break;
                case 'staff.department.name':
                    $query->orderBy(
                        StaffEmploy::select('departments.name')
                            ->join('departments', 'departments.id', '=', 'staff_employs.department_id')
                            ->whereColumn('staff_employs.user_id', 'users.id')
                            ->latest('staff_employs.hire_date')
                            ->limit(1),
                        $sortDirection
                    );
                    break;
                case 'staff.rank':
                case 'staff.job_title':
                    $field = str_replace('staff.', '', $orderBy);
                    $query->orderBy(
                        StaffEmploy::select($field)
                            ->whereColumn('staff_employs.user_id', 'users.id')
                            ->latest('staff_employs.hire_date')
                            ->limit(1),
                        $sortDirection
                    );
                    break;
            }
        }

        // Paginate results
        $users = $query->paginate($itemsPerPage);

        return response()->json([
            'result' => $users
        ], 200);
    }

    public function show(User $user)
    {
        // $data = User::with(['staff' => function($query){
        //     $query->select('department_id');
        // }])
        //         ->whereHas('staff')

        //         // "department_id": 1,
        //         // "job_title": "job_title",
        //         // "rank": null,
        //         // "hire_date": "2024-03-01",
        //         // "specialty": "specialty",
        //         // "subspecialty": "subspecialty",
        //         // "specialty_en": "specialty_en",
        //         // "subspecialty_en": "subspecialty_en",
        //         // "department": {

        // $data = User::with(['staff','staff.department:id,name,college_id','staff.department.college:id,name'])

        $data = User::where('id', $user->id)
            // ->whereHas('staff')
            // ->whereHas('staff.department', function ($query) {
            //     $query->where('active', 1);
            // })
            // ->whereHas('staff.department.college', function ($query) {
            //     $query->where('active', 1);
            // })
            ->select('id', 'name', 'name_en', 'univ_no', 'email', 'phone', 'role', 'img', 'thumb_img', 'active')->first();

        return response()->json(['result' => $data, 'status' => 200]);


        // $edit_data = DB::table('users')
        //     ->join('staff_employs', 'users.id', '=', 'staff_employs.id')
        //     ->join('departments', 'staff_employs.department_id', '=', 'departments.id')
        //     ->join('colleges', 'departments.college_id', '=', 'colleges.id')
        //     ->where('users.id', $id)
        //     ->whereNull('users.deleted_at')
        //     ->whereNull('staff_employs.deleted_at')
        //     ->whereNull('departments.deleted_at')
        //     ->whereNull('colleges.deleted_at')
        //     ->select(
        //         'users.id as id',
        //         'users.name as name',
        //         'users.name_en as name_en',
        //         'email',
        //         'phone',
        //         'role',
        //         'college_id',
        //         'colleges.name as college_name',
        //         'department_id',
        //         'departments.name as department_name',
        //         'rank',
        //         'img',
        //         'hire_date',
        //         'job_title',
        //         'specialty',
        //         'subspecialty',
        //         'univ_no',
        //         'specialty_en',
        //         'subspecialty_en',
        //         'users.active as active'
        //     )
        //     ->get();
        // if ($edit_data != '') {
        //     $data = DB::table('users')
        //         ->join('staff_employs', 'users.id', '=', 'staff_employs.id')
        //         ->join('departments', 'staff_employs.department_id', '=', 'departments.id')
        //         ->join('colleges', 'departments.college_id', '=', 'colleges.id')
        //         ->whereNull('users.deleted_at')
        //         ->select('users.id as id', 'users.name as name', 'email', 'role', 'colleges.name as college_name', 'colleges.id as college_id', 'departments.name as department_name', 'users.active as active')->get();
        //     $departments = Department::select('id', 'name')->where('college_id', $edit_data[0]->college_id)->get();
        //     $colleges = College::select('id', 'name')->get();
        //     return response()->json(['result' => $edit_data, 'status' => 200]);
        //     return view('admin.users')->with('data', $data)->with('colleges', $colleges)->with('departments', $departments)->with('edit_data', $edit_data);
        // } else {
        //     return redirect()->route('user.index');
        // }
    }

    public function store(UserRequest $userRequest)
    {
        $user_validator = $userRequest->validated();
        $active = 0;
        if (isset($userRequest->active) && $userRequest->active == 1) {
            $active = 1;
        }

        $img_path = $thumb_path = '';

        if ($userRequest->hasFile('img') && $userRequest->file('img')->isValid()) {
            try {
                $manager = new ImageManager(new Driver());
                $img = $userRequest->file('img');
                $rand = hexdec(uniqid());
                $imagename = $rand . '.' . $img->getClientOriginalExtension();
                $imagename_thumb = $rand . '_thumb.' . $img->getClientOriginalExtension();

                $img->storeAs('images/staff', $imagename, 'public');
                $manager->read($img)->scale(width: 300)->save(public_path('images/staff_thumbnail/' . $imagename_thumb));
                $img_path = 'images/staff/' . $imagename;
                $thumb_path = 'images/staff_thumbnail/' . $imagename_thumb;
            } catch (Exception $e) {
                return $e;
            }
        }

        try {
            $last_user_id = User::insertGetId(array_merge(
                $user_validator,
                ['univ_no' => $userRequest->univ_no],
                ['auth_id' => Auth::user()->id],
                ['active' => $active],
                ['password' => bcrypt('Staff@sustech123')],
                ['img' => $img_path],
                ['thumb_img' => $thumb_path]
            ));
        } catch (Exception $e) {
            return $e;
        }

        return response()->json(['message' => 'created', 'user_id' => $last_user_id, 'status' => 201]);
    }

    public function update(UserRequest $request, User $user)
    {
        $record = user::findOrFail($user->id);

        $active = 0;
        if (isset($request->active) && $request->active == 1) {
            $active = 1;
        }

        $img_path = $record->img;
        $thumb_path = $record->thumb_img;
        if ($request->hasFile('img') && $request->file('img')->isValid()) {
            try {
                $manager = new ImageManager(new Driver());
                $img = $request->file('img');
                $rand = hexdec(uniqid());
                $imagename = $rand . '.' . $img->getClientOriginalExtension();
                $imagename_thumb = $rand . '_thumb.' . $img->getClientOriginalExtension();

                if (Str::length($img_path) > 0 && file_exists(public_path($img_path))) {
                    unlink($img_path);
                }

                if (Str::length($thumb_path) > 0 && file_exists(public_path($thumb_path))) {
                    unlink($thumb_path);
                }

                $img->storeAs('images/staff', $imagename, 'public');
                $manager->read($img)->scale(width: 300)->save(public_path('images/staff_thumbnail/' . $imagename_thumb));

                $img_path = 'images/staff/' . $imagename;
                $thumb_path = 'images/staff_thumbnail/' . $imagename_thumb;
            } catch (Exception $e) {
            }
        }

        $record->name = $request->name;
        $record->name_en = $request->name_en;
        $record->univ_no = $request->univ_no;
        $record->email = $request->email;
        $record->phone = $request->phone;
        $record->role = $request->role;
        $record->active = $active;
        $record->img = $img_path;
        $record->thumb_img = $thumb_path;
        if ($record->isDirty()) { // if record data changed
            $record->save();
            return response()->json(['message' => 'updated', 'status' => 204]);
        } else {
            return response()->json(['message' => 'not changed', 'status' => 304]);
        }
    }

    public function destroy(User $user)
    {
        StaffEmploy::firstWhere('id', $user->id)->delete();
        $user::find($user->id)->delete();
        return redirect()->route('user.index');
    }

    public function list(Request $request)
    {
        $data = User::select('id', 'name')->whereHas('staff', function ($query) use ($request) {
            $query->where('department_id', DB::raw('"' . $request->department_id . '"'));
        })->get()->makeVisible(['id']);
        return response()->json([
            'users' => $data
        ], 200);
    }
}
