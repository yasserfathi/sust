<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;
use App\Models\StaffAlbumPhoto;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Http\Requests\StaffAlbumPhotoRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StaffAlbumPhotoController extends Controller
{
    // public function index()
    // {
    //     if(Auth::user()->role == 1){
    //         $data = StaffAlbumPhoto::select('id', 'title', 'img', 'thumb_img','active')->with('user.staff.department.college')->orderByRaw(DB::raw("FIELD(active,0,1) DESC"))->get();
    //     } elseif(Auth::user()->role == 3){
    //         $data = StaffAlbumPhoto::select('id', 'title', 'img', 'thumb_img','active')->with('user.staff.department.college')->where('user_id', Auth::user()->id)
    //                 ->orderByRaw(DB::raw("FIELD(active,0,1) DESC"))->get();
    //     }
    //     $colleges = College::select('id', 'name')->get();
    //     return view('admin.staff_album_photos')->with('data', $data)->with('colleges', $colleges);
    // }


    public function index(Request $request)
    {
        $itemsPerPage = htmlspecialchars($request->get('items') ?? 15);

        if ($itemsPerPage <= 0) {
            $itemsPerPage = 1000;
        }

        $search = htmlspecialchars($request->get('search') ?? '');

        $result = StaffAlbumPhoto::select('user_id', DB::raw('count(user_id) as total_images'))->groupBy('user_id')
            ->whereHas(
                'user',
                function ($query) use ($search) {
                    if (!empty($search)) {
                        $query->where('name', 'like', '%' . $search . '%');
                    }
                },
            )->whereHas('user.staff_latest.department.college')
            ->with('user:id,name');

        if (Auth::user()->role == 3) {
            $result->where('user_id', Auth::user()->id);
        }

        if ($itemsPerPage == 0) {
            $itemsPerPage = count($result);
        }

        return response()->json(['result' => $result->paginate((int)$itemsPerPage)], 200);
    }

    public function show($id)
    {
        // $result = StaffAlbumPhoto::select('id', 'user_id', 'title', 'title_en', 'img', 'thumb_img')
        //     ->with([
        //         'user' => function ($query) {
        //             $query->select('id', 'name');
        //         },
        //         'user.staff' => function ($query) {
        //             $query->select('id', 'department_id');
        //         },
        //         'user.staff.department' => function ($query) {
        //             $query->select('id', 'college_id', 'name');
        //         },
        //         'user.staff.department.college' => function ($query) {
        //             $query->select('id', 'name');
        //         },
        //     ])
        //     ->whereHas('user')->whereHas('user.staff')->whereHas('user.staff.department')
        //     ->where('user_id', DB::raw('"' . $id . '"'))->get();


        $result = StaffAlbumPhoto::select('id', 'user_id', 'title', 'title_en', 'img', 'thumb_img')
            ->whereHas('user.staff_latest.department')
            ->where('user_id', $id)->get();

        $user = User::select('id', 'name')->with([
            'staff' => function ($query) {
                $query->select('id');
            },
            'staff_latest.department' => function ($query) {
                $query->select('id', 'college_id', 'name');
            },
            'staff_latest.department.college' => function ($query) {
                $query->select('id', 'name');
            },
        ])->where('id', $id)->first();

        $result->each(function ($item) use ($user) {
            $item->department_id = $user->staff_latest->department_id;
            $item->department_name = $user->staff_latest->department->name;
            $item->college_id = $user->staff_latest->department->college->id;
            $item->college_name = $user->staff_latest->department->college->name;
        });

        return response()->json(['result' => $result, 'status' => 200]);
    }

    public function store(StaffAlbumPhotoRequest $request)
    {

        if (Auth::user()->role == 3 && $request->user_id === null) {
            $request->request->add(['user_id' => Auth::user()->id]);
        }

        $validator = $request->validated();
        $img_path = $thumb_path = '';
        if ($request->hasFile('img') && $request->file('img')->isValid()) {
            try {
                $manager = new ImageManager(new Driver());
                $img = $request->file('img');
                $rand = hexdec(uniqid());
                $imagename = $rand . '.' . $img->getClientOriginalExtension();
                $imagename_thumb = $rand . '_thumb.' . $img->getClientOriginalExtension();

                $manager->read($img)->scale(width: 300)->save(public_path('images/staff_albums_thumbnail/' . $imagename_thumb));
                $img->storeAs('images/staff_albums', $imagename, 'public');

                $img_path = 'images/staff_albums/' . $imagename;
                $thumb_path = 'images/staff_albums_thumbnail/' . $imagename_thumb;
            } catch (Exception $e) {
                return response()->json(['message' => 'Error processing file: ' . $e->getMessage(), 'status' => 500], 500);
            }
        } else {
            return response()->json(['message' => ['img' => 'الصورة مطلوبة'], 'status' => 409], 200);
        }

        StaffAlbumPhoto::create(array_merge(
            $validator,
            ['auth_id' => Auth::user()->id],
            ['img' => $img_path],
            ['thumb_img' => $thumb_path],
        ));
        return response()->json(['message' => 'created', 'status' => 201]);
    }

    public function destroy(StaffAlbumPhoto $StaffAlbumPhoto)
    {
        if (Str::length($StaffAlbumPhoto->img) > 0 && file_exists(public_path($StaffAlbumPhoto->img))) {
            unlink(public_path($StaffAlbumPhoto->img));
        }

        if (Str::length($StaffAlbumPhoto->thumb_img) > 0 && file_exists(public_path($StaffAlbumPhoto->thumb_img))) {
            unlink(public_path($StaffAlbumPhoto->thumb_img));
        }
        $StaffAlbumPhoto->delete();
        return response()->json(['message' => 'deleted', 'status' => 200]);
    }

    public function active(Request $request)
    {
        $photo = StaffAlbumPhoto::find($request->id);
        $count = StaffAlbumPhoto::select('id')->where('active', 1)->where('user_id', $photo->user_id)->count();
        if ($photo->active == 0 && $count < 5 || $photo->active == 1) {
            $photo->active = !$photo->active;
            $photo->save();
        }
        return response()->json(['message' => 'activated', 'status' => 200]);
    }
}
