<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Exception;
use App\Models\Ad;
use App\Models\AlbumPhoto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AdRequest;
use Illuminate\Support\Str;

class AdController extends Controller
{
    public function index(Request $request)
    {
        $itemsPerPage = htmlspecialchars($request->get('items') ?? 15);
        if ($itemsPerPage <= 0) {
            $itemsPerPage = 1000;
        }
        $search = htmlspecialchars($request->get('search') ?? '');
        $search = trim(preg_replace('/[+\-><\(\)~*\"@]+/', ' ', $search));

        $query = Ad::select('id', 'college_id', 'ad_date', 'duration', 'active', 'priority', \DB::raw('SUBSTRING(`title`, 1, 80) as `title`'))
            ->with('college:id,name')
            ->whereHas('college', function ($q) {
                $authUser = Auth::user();
                if ($authUser->role != 1) {
                    if ($authUser->is_college_rep) {
                        $collegeId = $authUser->staff_latest_by_id?->department?->college_id;
                        $q->where('id', $collegeId);
                    } else {
                        $q->where('user_id', $authUser->id);
                    }
                }
            })
            ->where('lang', 1);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereRaw('MATCH(title) AGAINST(? IN BOOLEAN MODE)', [implode(' ', array_map(function($w) { $w = trim(preg_replace('/[+\-\><\(\)~*"@]+/', '', $w)); if (!$w) return ''; $prefixes = ['', 'ال', 'وال', 'بال', 'فال', 'لل', 'كال']; $group = []; foreach($prefixes as $p) { $group[] = $p . $w . '*'; } return '+(' . implode(' ', $group) . ')'; }, explode(' ', $search)))])
                    ->orWhere('ad_date', 'like', '%' . $search . '%')
                    ->orWhereHas('college', function ($q2) use ($search) {
                        $q2->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($request->exists('orderby') && $request->exists('ascend')) {
            $query->orderBy($request->get('orderby'), in_array(strtolower(trim($request->get('ascend') ?? '')), ['asc', 'true', '1']) ? 'asc' : 'desc');
        } else {
            $query->orderByDesc('id');
        }

        return response()->json(['result' => $query->paginate((int)$itemsPerPage)], 200);
    }

    public function store(AdRequest $request)
    {
        $request->ad_date = Carbon::parse($request->ad_date);
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
        $ad = Ad::create(array_merge(
            $validator,
            ['slug' => Str::slug($request->title, '-')],
            ['auth_id' => Auth::user()->id],
            ['active' => $active],
            ['file' => $file_path],
            ['lang' => 1],
        ));

        if ($request->has('photos')) {
            $photos = $request->photos;
            if (is_string($photos)) {
                $photos = explode(',', $photos);
            }
            $ad->photos()->sync($photos);
        }

        return response()->json(['message' => 'created', 'status' => 201]);
    }

    public function show(Ad $ad)
    {
        $edit_data = Ad::select('id', 'college_id', 'title', 'slug', 'ad_date', 'duration', 'active', 'detail_portion', 'detail', 'file')
            ->with(['college:id,name', 'photos:id,thumb_img as thumb_photo,img as photo,title'])->where('id', $ad->id)->first();

        return response()->json(['result' => $edit_data, 'status' => 200]);
    }

    public function update(AdRequest $request, Ad $ad)
    {
        $record = Ad::find($ad->id);
        $file_path = $record->file;
        if ($request->hasFile('file')) {
            if ($request->file('file')->isValid()) {
                try {
                    $file = $request->file('file');
                    $filename = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
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
        $record->slug = Str::slug($request->title, '-');
        $record->college_id = $request->college_id;
        $record->ad_date = Carbon::parse($request->ad_date);
        $record->duration = $request->duration;
        $record->active = $active;
        $record->keywords = $request->keywords;
        $record->detail_portion = $request->detail_portion;
        $record->detail = $request->detail;
        $record->file = $file_path;
        $record->auth_id = Auth::user()->id;

        if ($record->isDirty()) { // if record data changed
            $record->save();
        }

        if ($request->has('photos')) {
            $photos = $request->photos;
            if (is_string($photos)) {
                $photos = explode(',', $photos);
            }
            $record->photos()->sync($photos);
        }

        return response()->json(['message' => 'updated', 'status' => 204]);
    }

    public function destroy(string $id)
    {
        $data = Ad::find($id);
        if ($data && Str::length($data->file) > 0 && file_exists(public_path($data->file))) {
            unlink(public_path($data->file));
        }
        if ($data) {
            $data->delete();
        }
        return response()->json(['message' => 'deleted', 'status' => 200]);
    }

    public function priority($id)
    {
        $ad = Ad::select('id', 'priority')->find($id);
        $ad->priority = (int)!$ad->priority;
        $ad->save();
        return response()->json(['message' => 'updated', 'status' => 204]);
    }
}
