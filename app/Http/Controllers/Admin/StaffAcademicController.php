<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaffAcademic;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Http\Requests\StaffAcademicRequest;
use Illuminate\Support\Str;

class StaffAcademicController extends Controller
{

    protected $page;

    public function __construct(Request $request)
    {
        $this->page = $request->segment(3);
    }

    public function index(Request $request)
    {
        $itemsPerPage = htmlspecialchars($request->get('items') ?? 15);

        if ($itemsPerPage <= 0) {
            $itemsPerPage = 1000;
        }

        $search = htmlspecialchars($request->get('search') ?? '');

        $select_fileds = ['id', 'item_val', 'type', DB::raw('(CASE WHEN lang = 1 THEN "اللغة العربية" ELSE "اللغة الانجليزية" END) AS lang'), 'user_id'];

        if ($this->page == 'links' || $this->page == 'google_scholar') {
            array_push($select_fileds, 'url');
        }

        //collection serach User Name
        $result_user_name = StaffAcademic::select($select_fileds)
            ->whereHas('user')->with('user:id,name')->where('item', $this->page);

        if (!empty($search)) {
            $result_user_name->where('item_val', 'like', '%' . $search . '%');
        }

        if (Auth::user()->role == 3) {
            $result_user_name->where('user_id', Auth::user()->id);
        }

        // ===================================

        //collection serach Item Value;
        $result_item_value = StaffAcademic::select($select_fileds)
            ->whereHas(
                'user',
                function ($query) use ($search) {
                    if (!empty($search)) {
                        $query->where('name', 'like', '%' . $search . '%');
                    }
                },
            )
            ->where('item_val', 'like', '%' . $search . '%')
            ->with('user:id,name')
            ->where('item', $this->page);
        
        if (!empty($search)) {
            $result_item_value->where('item_val', 'like', '%' . $search . '%');
        }
    
        if (Auth::user()->role == 3) {
            $result_item_value->where('user_id', Auth::user()->id);
        }

        // $all = $result_item_value->get();

        if ($request->exists('orderby') && $request->exists('ascend')) {
            $result_item_value->sortBy([[$request->get('orderby'), $request->get('ascend')]]);
        }

       // $itemsPerPage = 15;

        return response()->json(['result' => $result_item_value->paginate((int)$itemsPerPage)], 200);
    }

    public function show($slug, $id)
    {
        $recordId = $id instanceof StaffAcademic ? $id->id : $id;

        $edit_data = StaffAcademic::select(
            'staff_academics.id',
            'staff_academics.lang',
            'staff_academics.user_id',
            'staff_academics.item_val',
            'staff_academics.url',
            'staff_academics.img',
            'staff_academics.thumb_img',
            'staff_academics.file',
            'staff_academics.detail'
        )->with([
            'user' => function ($query) {
                $query->select('id', 'name')->withTrashed();
            },
            'user.staff_latest' => function ($query) {
                $query->select('staff_employs.id', 'staff_employs.user_id', 'staff_employs.department_id')->withTrashed();
            },
            'user.staff_latest.department' => function ($query) {
                $query->select('id', 'college_id', 'name')->withTrashed();
            },
            'user.staff_latest.department.college' => function ($query) {
                $query->select('id', 'name')->withTrashed();
            }
        ])
            ->where([['staff_academics.id', $recordId], ['staff_academics.item', $slug]])->first();

        return response()->json(['result' => $edit_data, 'status' => 200]);
    }

    public function store(StaffAcademicRequest $request)
    {
        $validator = $request->validated();
        $img_path = $thumb_path = $url = $detail = '';
        if ($request->hasFile('img')) {
            if ($request->file('img')->isValid()) {
                try {
                    $manager = new ImageManager(new Driver());
                    $img = $request->file('img');
                    $rand = hexdec(uniqid());
                    $imagename = $rand . '.' . $img->getClientOriginalExtension();
                    $imagename_thumb = $rand . '_thumb.' . $img->getClientOriginalExtension();

                    $manager->read($img)->scale(width: 300)->save(public_path('images/staff_academic_thumbnail/' . $imagename_thumb));
                    $img->storeAs('images/staff_academic', $imagename, 'public');

                    $img_path = 'images/staff_academic/' . $imagename;
                    $thumb_path = 'images/staff_academic_thumbnail/' . $imagename_thumb;
                } catch (Exception $e) {
                return response()->json(['message' => 'Error processing file: ' . $e->getMessage(), 'status' => 500], 500);
            }
            }
        }

        $file_path = '';
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            try {
                $file = $request->file('file');
                $rand = hexdec(uniqid());
                $imagename = $rand . '.' . $file->getClientOriginalExtension();
                $file_path = $file->storeAs('files', $imagename, 'public');
            } catch (Exception $e) {
                return response()->json(['message' => 'Error processing file: ' . $e->getMessage(), 'status' => 500], 500);
            }
        }

        if ($request->has('url')) {
            $url = $request->url;
        }

        if ($request->has('detail')) {
            $detail = $request->detail;
        }

        // if ($this->page == 'google_scholar') {
        //     $data_exists_id = StaffAcademic::whereColumn([
        //         ['item', '=', DB::raw('"' . $this->page . '"')],
        //         ['item_val', '=', DB::raw('"' . $request->item_val . '"')], ['user_id', '=', DB::raw('"' . $request->user_id . '"')]
        //     ])->first();

        //     if ($data_exists_id != null) {
        //         $record = StaffAcademic::find($data_exists_id->id);
        //         $record->auth_id = Auth::user()->id;
        //         $record->url = $request->url;
        //         $record->lang = 1;
        //         $record->save();
        //         return "success";
        //     }
        // }

        StaffAcademic::create(array_merge(
            $validator,
            ['lang' =>  $request->lang],
            ['item' => $this->page],
            ['type' => $request->has('type') ? $request->type : null],
            ['auth_id' => Auth::user()->id],
            ['img' => $img_path],
            ['file' => $file_path],
            ['thumb_img' => $thumb_path],
            ['url' => $url],
            ['detail' => $detail],
        ));

        return response()->json(['message' => 'created', 'status' => 201]);
    }

    public function update(StaffAcademicRequest $request, $slug, string $id)
    {
        $record = StaffAcademic::where('item', $slug)->findOrFail($id);

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

                    $manager->read($img)->scale(width: 300)->save(public_path('images/staff_academic_thumbnail/' . $imagename_thumb));
                    $img->storeAs('images/staff_academic', $imagename, 'public');

                    $img_path = 'images/staff_academic/' . $imagename;
                    $thumb_path = 'images/staff_academic_thumbnail/' . $imagename_thumb;
                } catch (Exception $e) {
                    return response()->json(['message' => 'Error processing file: ' . $e->getMessage(), 'status' => 500], 500);
                }
            } else {
                return response()->json(['message' => ['img' => 'عفوا، حجم الصورة يتجاوز الحد المسموح به أو الملف غير صالح'], 'status' => 409], 200);
            }
        }

        $file_path = $record->file;
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            try {
                $file = $request->file('file');
                $rand = hexdec(uniqid());
                $imagename = $rand . '.' . $file->getClientOriginalExtension();

                if (Str::length($file_path) > 0 && file_exists(public_path($file_path))) {
                    unlink(public_path($file_path));
                }
                $file_path = $file->storeAs('files', $imagename, 'public');
            } catch (Exception $e) {
                return response()->json(['message' => 'Error processing file: ' . $e->getMessage(), 'status' => 500], 500);
            }
        }

        $record->user_id = $request->user_id;
        $record->lang = $request->lang;
        $record->item_val = $request->item_val;
        $record->type = $request->has('type') ? $request->type : null;
        $record->url = $request->url;
        $record->detail = $request->detail;
        $record->img = $img_path;
        $record->thumb_img = $thumb_path;
        $record->file = $file_path;
        $record->auth_id = Auth::user()->id;

        if ($record->isDirty()) { // if record data changed
            $record->save();
            return response()->json(['message' => 'updated', 'status' => 204]);
        } else {
            return response()->json(['message' => 'not changed', 'status' => 304]);
        }
    }

    public function destroy($slug, StaffAcademic $staffAcademic)
    {
        if ($staffAcademic->item !== $slug) {
            return response()->json(['message' => 'العنصر غير موجود أو تم حذفه مسبقاً', 'status' => 404], 404);
        }

        if (Str::length($staffAcademic->img) > 0 && file_exists(public_path($staffAcademic->img))) {
            unlink(public_path($staffAcademic->img));
        }

        if (Str::length($staffAcademic->thumb_img) > 0 && file_exists(public_path($staffAcademic->thumb_img))) {
            unlink(public_path($staffAcademic->thumb_img));
        }

        if (Str::length($staffAcademic->file) > 0 && file_exists(public_path($staffAcademic->file))) {
            unlink(public_path($staffAcademic->file));
        }

        $staffAcademic->delete();
        return response()->json(['message' => 'deleted', 'status' => 200]);
    }
}
