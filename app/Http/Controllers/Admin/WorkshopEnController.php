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

class WorkshopEnController extends Controller
{
    public function index(Request $request)
    {
        $itemsPerPage = (int) htmlspecialchars($request->get('items') ?? 15);
        if ($itemsPerPage <= 0) {
            $itemsPerPage = 1000;
        }
        $search = htmlspecialchars($request->get('search') ?? '');
        $search = trim(preg_replace('/[+\-><\(\)~*\"@]+/', ' ', $search));

        $query = Workshop::select('workshops.id', 'workshops.college_id', 'workshops.title', 'workshops.type', 'workshops.workshop_date', 'workshops.active')
            ->selectRaw('SUBSTRING(workshops.`title`, 1, 80) as `title`')
            ->with('college:id,name')
            ->where('workshops.lang', 2);

        $authUser = Auth::user();
        if ($authUser->role != 1) {
            $query->whereHas('college', function ($q) use ($authUser) {
                if ($authUser->is_college_rep) {
                    $collegeId = $authUser->staff_latest_by_id?->department?->college_id;
                    $q->where('id', $collegeId);
                } else {
                    $q->where('user_id', $authUser->id);
                }
            });
        } else {
            $query->whereHas('college');
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereRaw('MATCH(workshops.title) AGAINST(? IN BOOLEAN MODE)', [implode(' ', array_map(function($w) { $w = trim(preg_replace('/[+\-\><\(\)~*"@]+/', '', $w)); if (!$w) return ''; $prefixes = ['', 'ال', 'وال', 'بال', 'فال', 'لل', 'كال']; $group = []; foreach($prefixes as $p) { $group[] = $p . $w . '*'; } return '+(' . implode(' ', $group) . ')'; }, explode(' ', $search)))])
                  ->orWhere('workshops.workshop_date', 'like', '%' . $search . '%')
                  ->orWhereHas('college', function ($cq) use ($search) {
                      $cq->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        if ($request->has('orderby') && $request->has('ascend')) {
            $orderBy = $request->get('orderby');
            $sortDirection = ($request->get('ascend') === 'true' || $request->get('ascend') === 'asc' || $request->get('ascend') == '1') ? 'asc' : 'desc';

            if ($orderBy === 'college.name') {
                $query->join('colleges', 'colleges.id', '=', 'workshops.college_id')
                      ->orderBy('colleges.name', $sortDirection);
            } else {
                $query->orderBy('workshops.' . ltrim($orderBy, 'workshops.'), $sortDirection);
            }
        } else {
            $query->orderBy('workshops.id', 'desc');
        }

        return response()->json(['result' => $query->paginate($itemsPerPage)], 200);
    }

    public function show(Workshop $workshop)
    {
        $data = Workshop::select('id', 'college_id', 'title', 'type', 'workshop_date', 'active', 'detail_portion', 'detail', 'file')
            ->with(['college:id,name', 'photos:id,thumb_img as thumb_photo,img as photo,title'])
            ->where('id', $workshop->id)
            ->first();

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
                return response()->json(['message' => 'Error processing file: ' . $e->getMessage(), 'status' => 500], 500);
            }
        }
        Workshop::create(array_merge(
            $validator,
            ['auth_id' => Auth::user()->id],
            ['active' => $active],
            ['file' => $file_path],
            ['lang' => 2],
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
                        unlink(public_path($record->file));
                    }
                } catch (Exception $e) {
                return response()->json(['message' => 'Error processing file: ' . $e->getMessage(), 'status' => 500], 500);
            }
            }
        }
        $active = 0;
        if (isset($request->active) && $request->active == 1) {
            $active = 1;
        }
        $record->title = $request->title;
        $record->type = $request->type ?? 1;
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
        if (Str::length($workshop->file) > 0 && file_exists(public_path($workshop->file))) {
            unlink(public_path($workshop->file));
        }
        $workshop->delete();
        return response()->json(['message' => 'deleted', 'status' => 200]);
    }
}
