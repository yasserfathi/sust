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
use App\Models\Staff_resume;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
            'ascend' => 'sometimes|in:asc,desc,true,false,1,0'
        ]);

        $itemsPerPage = $validated['items'] ?? 15;
        $search = $validated['search'] ?? '';
        $orderBy = $validated['orderby'] ?? null;
        $sortDirection = in_array($request->input('ascend'), ['desc', 'false', false, '0', 0], true) ? 'desc' : 'asc';

        $query = User::query()
            ->with([
                'staff' => function ($query) {
                    $query->select('id', 'user_id', 'grade', 'department_id', 'hire_date');
                },
                'staff.department' => function ($query) {
                    $query->select('id', 'college_id', 'name', 'name_en');
                },
                'staff.department.college' => function ($query) {
                    $query->select('id', 'name', 'name_en');
                }
            ]);

        $authUser = Auth::user();
        if ($authUser && $authUser->role != 1 && $authUser->is_college_rep) {
            $collegeId = $authUser->staff_latest_by_id?->department?->college_id;
            if ($collegeId) {
                $query->whereHas('staff.department', function ($q) use ($collegeId) {
                    $q->where('college_id', $collegeId);
                });
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        // Apply search conditions
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('staff', function ($q) use ($search) {
                    $q->where('grade', 'like', "%{$search}%")
                      ->orWhere('grade_en', 'like', "%{$search}%");
                })
                    ->orWhereHas('staff.department', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                          ->orWhere('name_en', 'like', "%{$search}%");
                    })
                    ->orWhereHas('staff.department.college', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                          ->orWhere('name_en', 'like', "%{$search}%");
                    })
                    ->orWhereRaw('MATCH(name, name_en) AGAINST(? IN BOOLEAN MODE)', [implode(' ', array_map(function($w) { $w = trim(preg_replace('/[+\-\><\(\)~*"@]+/', '', $w)); if (!$w) return ''; $prefixes = ['', 'ال', 'وال', 'بال', 'فال', 'لل', 'كال']; $group = []; foreach($prefixes as $p) { $group[] = $p . $w . '*'; } return '+(' . implode(' ', $group) . ')'; }, explode(' ', $search)))])
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
                case 'staff.grade':
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

        $users->getCollection()->transform(function ($user) {
            $user->is_default_password = false; // Disabled to prevent slow performance on large datasets
            return $user;
        });

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
            ->select('id', 'name', 'name_en', 'univ_no', 'email', 'phone', 'role', 'img', 'thumb_img', 'active', 'is_college_rep')->first();

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

                $manager->read($img)->scale(width: 300)->save(public_path('images/staff_thumbnail/' . $imagename_thumb));
                $img->storeAs('images/staff', $imagename, 'public');
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
                ['thumb_img' => $thumb_path],
                ['is_college_rep' => isset($userRequest->is_college_rep) && $userRequest->is_college_rep ? 1 : 0],
                ['slug' => str_replace(' ', '-', trim($userRequest->name_en))]
            ));
        } catch (Exception $e) {
            return $e;
        }

        return response()->json(['message' => 'created', 'user_id' => $last_user_id, 'status' => 201]);
    }

    public function update(UserRequest $request, User $user)
    {
        $record = User::findOrFail($user->id);

        $active = 0;
        if (isset($request->active) && $request->active == 1) {
            $active = 1;
        }

        $is_college_rep = 0;
        if (isset($request->is_college_rep) && $request->is_college_rep == 1) {
            $is_college_rep = 1;
        }

        $img_path = $record->img;
        $thumb_path = $record->thumb_img;
        if ($request->hasFile('img')) {
            if ($request->file('img')->isValid()) {
                try {
                    $manager = new ImageManager(new Driver());
                    $img = $request->file('img');
                    $rand = hexdec(uniqid());
                    $imagename = $rand . '.' . $img->getClientOriginalExtension();
                    $imagename_thumb = $rand . '_thumb.' . $img->getClientOriginalExtension();

                    if (Str::length($img_path) > 0 && file_exists(public_path($img_path))) {
                        unlink(public_path($img_path));
                    }

                    if (Str::length($thumb_path) > 0 && file_exists(public_path($thumb_path))) {
                        unlink(public_path($thumb_path));
                    }

                    $manager->read($img)->scale(width: 300)->save(public_path('images/staff_thumbnail/' . $imagename_thumb));
                    $img->storeAs('images/staff', $imagename, 'public');

                    $img_path = 'images/staff/' . $imagename;
                    $thumb_path = 'images/staff_thumbnail/' . $imagename_thumb;
                } catch (Exception $e) {
                    return response()->json(['message' => 'Error processing file: ' . $e->getMessage(), 'status' => 500], 500);
                }
            } else {
                return response()->json(['message' => ['img' => 'عفوا، حجم الصورة يتجاوز الحد المسموح به أو الملف غير صالح'], 'status' => 409], 200);
            }
        }
        $record->name = $request->name;
        $record->name_en = $request->name_en;
        $record->univ_no = $request->univ_no;
        $record->email = $request->email;
        $record->phone = $request->phone;
        $record->role = $request->role;
        $record->active = $active;
        $record->is_college_rep = $is_college_rep;
        $record->img = $img_path;
        $record->thumb_img = $thumb_path;
        $record->auth_id = Auth::user()->id;
        if ($record->isDirty()) { // if record data changed
            $record->save();
            return response()->json(['message' => 'updated', 'status' => 204]);
        } else {
            return response()->json(['message' => 'not changed', 'status' => 304]);
        }
    }

    public function destroy(User $user)
    {
        StaffEmploy::where('user_id', $user->id)->delete();
        $user->delete();
        return response()->json(['message' => 'deleted successfully', 'status' => 200]);
    }

    public function list(Request $request)
    {
        $data = User::select('id', 'name')->whereHas('staff', function ($query) use ($request) {
            $query->where('department_id', $request->department_id);
        })->get()->makeVisible(['id']);
        return response()->json([
            'users' => $data
        ], 200);
    }

    public function changePassword(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'password_change' => 'required|min:6',
            'password_change_confirm' => 'required|same:password_change'
        ]);

        $user = $request->user();
        $user->password = \Illuminate\Support\Facades\Hash::make($request->password_change);
        $user->save();

        return response()->json(['success' => true, 'message' => 'تم تغيير كلمة المرور بنجاح'], 200);
    }

    public function getProfile(Request $request)
    {
        $user = $request->user();
        $user->load([
            'staff_latest.department.college',
        ]);

        $resume = Staff_resume::where('user_id', $user->id)->first();

        return response()->json([
            'user' => $user,
            'resume' => $resume,
            'status' => 200
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'email' => ['required', 'email', \Illuminate\Validation\Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', \Illuminate\Validation\Rule::unique('users')->ignore($user->id)],
            'img' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'resume_ar' => 'nullable|file|mimes:doc,docx,pdf|max:10240',
            'resume_en' => 'nullable|file|mimes:doc,docx,pdf|max:10240',
        ], [
            'email.unique' => 'البريد الإلكتروني مستخدم مسبقاً',
            'phone.unique' => 'رقم الهاتف مستخدم مسبقاً',
            'img.image' => 'يجب اختيار صورة صالحة',
            'img.max' => 'الحد الأقصى لحجم الصورة هو 5 ميجابايت',
            'resume_ar.mimes' => 'يتم دعم ملفات .doc, .docx, .pdf فقط للسيرة الذاتية بالعربية',
            'resume_en.mimes' => 'يتم دعم ملفات .doc, .docx, .pdf فقط للسيرة الذاتية بالإنجليزية',
        ]);

        $user->name = $request->name;
        $user->name_en = $request->name_en;
        $user->email = $request->email;
        $user->phone = $request->phone;

        // Process Profile Image
        if ($request->hasFile('img') && $request->file('img')->isValid()) {
            try {
                $manager = new ImageManager(new Driver());
                $imgFile = $request->file('img');
                $rand = hexdec(uniqid());
                $imagename = $rand . '.' . $imgFile->getClientOriginalExtension();
                $imagename_thumb = $rand . '_thumb.' . $imgFile->getClientOriginalExtension();

                if (!empty($user->img) && file_exists(public_path($user->img))) {
                    @unlink(public_path($user->img));
                }
                if (!empty($user->thumb_img) && file_exists(public_path($user->thumb_img))) {
                    @unlink(public_path($user->thumb_img));
                }

                $manager->read($imgFile)->scale(width: 300)->save(public_path('images/staff_thumbnail/' . $imagename_thumb));
                $imgFile->storeAs('images/staff', $imagename, 'public');

                $user->img = 'images/staff/' . $imagename;
                $user->thumb_img = 'images/staff_thumbnail/' . $imagename_thumb;
            } catch (Exception $e) {
                return response()->json(['message' => 'حدث خطأ أثناء معالجة الصورة: ' . $e->getMessage(), 'status' => 500], 500);
            }
        }

        $user->save();

        // Process Resumes (CVs)
        $resume = Staff_resume::firstOrNew(['user_id' => $user->id]);

        if ($request->hasFile('resume_ar') && $request->file('resume_ar')->isValid()) {
            try {
                $fileAr = $request->file('resume_ar');
                $rand = hexdec(uniqid());
                $filenameAr = $rand . '_ar.' . $fileAr->getClientOriginalExtension();

                if (!empty($resume->file)) {
                    Storage::disk('public')->delete($resume->file);
                }
                $resume->file = $fileAr->storeAs('resumes', $filenameAr, 'public');
            } catch (Exception $e) {
                return response()->json(['message' => 'حدث خطأ أثناء حفظ السيرة الذاتية بالعربية', 'status' => 500], 500);
            }
        }

        if ($request->hasFile('resume_en') && $request->file('resume_en')->isValid()) {
            try {
                $fileEn = $request->file('resume_en');
                $rand = hexdec(uniqid());
                $filenameEn = $rand . '_en.' . $fileEn->getClientOriginalExtension();

                if (!empty($resume->file_en)) {
                    Storage::disk('public')->delete($resume->file_en);
                }
                $resume->file_en = $fileEn->storeAs('resumes', $filenameEn, 'public');
            } catch (Exception $e) {
                return response()->json(['message' => 'حدث خطأ أثناء حفظ السيرة الذاتية بالإنجليزية', 'status' => 500], 500);
            }
        }

        if ($resume->isDirty() || !$resume->exists) {
            $resume->user_id = $user->id;
            $resume->auth_id = $user->id;
            $resume->save();
        }

        $user->load(['staff_latest.department.college']);

        return response()->json([
            'message' => 'تم تحديث الملف الشخصي بنجاح',
            'user' => $user,
            'resume' => $resume,
            'status' => 200
        ]);
    }

    public function resetPassword(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->password = \Illuminate\Support\Facades\Hash::make($user->email);
        $user->save();

        return response()->json(['message' => 'تم اعادة تعيين كلمة المرور بنجاح', 'status' => 200]);
    }
}