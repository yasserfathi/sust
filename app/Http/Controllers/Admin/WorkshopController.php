<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Workshop;
use App\Models\AlbumPhoto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\WorkshopRequest;
use Illuminate\Support\Str;

class WorkshopController extends Controller
{
    public function index(Request $request)
    {
        $itemsPerPage = htmlspecialchars($request->get('items') ?? 15);
        if ($itemsPerPage < 0) {
            $itemsPerPage = 0;
        }
        $search = htmlspecialchars($request->get('search') ?? '');

        //collection serach Workshop Title
        $result_workshop_title = Workshop::select('id', 'college_id', 'workshop_date', 'active')
            ->selectRaw('SUBSTRING(`title`, 1, 80) as `title`');

        if (!empty($search)) {
            $result_workshop_title->where('title', 'like', '%' . $search . '%')
                ->orWhere('workshop_date', 'like', '%' . $search . '%');
        }

        $result_workshop_title->with('college:id,name')->whereHas('college', function ($query) {
            $query->where('user_id', 1);
        })->where('lang', 1)->orderBy('id', 'DESC');

        // ===================================

        //collection serach College Name
        $result_college_name = Workshop::select('id', 'college_id', 'title', 'workshop_date', 'active')
            ->selectRaw('SUBSTRING(`title`, 1, 80) as `title`');

        $result_college_name->with('college:id,name')->whereHas('college', function ($query) use ($search) {
            $query->where('user_id', 1);
            if (!empty($search)) {
                $query->where('name', 'like', '%' . $search . '%');
            }
        })->where('lang', 1)->orderBy('id', 'DESC');

        $all = $result_workshop_title->get()->merge($result_college_name->get());

        if ($request->exists('orderby') && $request->exists('ascend')) {
            $all = $all->sortBy([[$request->get('orderby'), $request->get('ascend')]]);
        }

        if ($itemsPerPage == 0) {
            $itemsPerPage = count($all);
        }

        return response()->json(['result' => $all->paginate((int)$itemsPerPage)], 200);
    }

    public function show(Workshop $workshop)
    {
        $data = Workshop::select('college_id', 'title', 'workshop_date', 'active', 'detail_portion', 'detail', 'photos', 'file')
            ->with('college:id,name')->where('id', $workshop->id)->first();

        $list_photos = AlbumPhoto::select('id', 'thumb_img as thumb_photo', 'img as photo', 'title')->whereIn('id', explode(',', $data->photos))->get();

        $data->photos = $list_photos;

        return response()->json(['result' => $data, 'status' => 200]);
    }

    public function store(WorkshopRequest $request)
    {
        $validator = $request->validated();

        $active = 0;
        if (isset($request->active) && $request->active == 1) {
            $active = 1;
        }

        $file_path = '';
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            try {
                $file = $request->file('file');
                $filename = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
                $file_path = $file->storeAs('files', $filename, 'public');
            } catch (Exception $e) {
            }
        }
        Workshop::create(array_merge(
            $validator,
            ['auth_id' => Auth::user()->id],
            ['active' => $active],
            ['file' => $file_path],
        ));
        return response()->json(['message' => 'created', 'status' => 201]);
    }

    public function update(WorkshopRequest $request, Workshop $workshop)
    {
        $record = Workshop::find($workshop->id);
        $file_path = $record->file;
        if ($request->hasFile('file')) {
            if ($request->file('file')->isValid()) {
                try {
                    $file = $request->file('file');
                    $rand = hexdec(uniqid());
                    $filename = $rand . '.' . $file->getClientOriginalExtension();
                    $file_path = $file->storeAs('files', $filename, 'public');
                    if (Str::length($record->file) > 0 && file_exists(public_path($record->file))) {
                        unlink($record->file);
                    }
                } catch (Exception  $e) {
                }
            }
        }
        $active = 0;
        if (isset($request->active) && $request->active == 1) {
            $active = 1;
        }
        $record->title = $request->title;
        $record->workshop_date = $request->workshop_date;
        $record->active = $active;
        $record->keywords = $request->keywords;
        $record->detail_portion = $request->detail_portion;
        $record->detail = $request->detail;
        $record->photos = $request->photos;
        $record->file = $file_path;
        $record->auth_id = Auth::user()->id;

        if ($record->isDirty()) { // if record data changed
            $record->save();
            return response()->json(['message' => 'updated', 'status' => 204]);
        }
        return response()->json(['message' => 'not changed', 'status' => 304]);
    }

    public function destroy(Workshop $workshop)
    {
        $data = Workshop::find($workshop->id);
        if (Str::length($data->file) > 0 && file_exists(public_path($data->file))) {
            unlink($data->file);
        }
        $data->delete();
        return response()->json(['message' => 'deleted', 'status' => 200]);
    }
}
