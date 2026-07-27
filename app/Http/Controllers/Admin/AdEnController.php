<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Exception;
use App\Models\Ad;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AdEnRequest;
use Illuminate\Support\Str;

class AdEnController extends Controller
{
    public function index(Request $request)
    {
        $itemsPerPage = htmlspecialchars($request->get('items') ?? 15);
        if ($itemsPerPage < 0) {
            $itemsPerPage = 0;
        }
        $search = htmlspecialchars($request->get('search') ?? '');

        $query = Ad::select('id', 'college_id', 'ad_date', 'duration', 'active', 'priority', \DB::raw('SUBSTRING(`title`, 1, 80) as `title`'))
            ->with('college:id,name')
            ->whereHas('college', function ($q) {
                $q->where('user_id', 1);
            })
            ->where('lang', 2);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('ad_date', 'like', '%' . $search . '%')
                    ->orWhereHas('college', function ($q2) use ($search) {
                        $q2->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($request->exists('orderby') && $request->exists('ascend')) {
            $query->orderBy($request->get('orderby'), $request->get('ascend'));
        } else {
            $query->orderBy('id', 'DESC');
        }

        return response()->json(['result' => $query->paginate((int) $itemsPerPage)], 200);
    }

    public function store(AdEnRequest $request)
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
            }
        }
        $ad = Ad::create(array_merge(
            $validator,
            ['slug' => Str::slug($request->title, '-')],
            ['auth_id' => Auth::user()->id],
            ['active' => $active],
            ['file' => $file_path],
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

    public function show($id)
    {
        // \Log::info('AdEnController show id: ' . $id);
        $edit_data = Ad::select('id', 'college_id', 'title', 'slug', 'ad_date', 'duration', 'active', 'detail_portion', 'detail', 'file')
            ->with(['college:id,name', 'photos:id,thumb_img as thumb_photo,img as photo,title'])->where('id', $id)->first();
        // \Log::info('AdEnController show result: ' . json_encode($edit_data));

        return response()->json(['result' => $edit_data, 'status' => 200]);
    }

    public function update(AdEnRequest $request, Ad $ad_en)
    {
        $record = Ad::find($ad_en->id);

        $file_path = $record->file;
        if ($request->hasFile('file')) {
            if ($request->file('file')->isValid()) {
                try {
                    $file = $request->file('file');
                    $filename = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
                    $file_path = $file->storeAs('files', $filename, 'public');
                    if (file_exists(public_path($record->file))) {
                        unlink($record->file);
                    }
                } catch (Exception $e) {
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
        if (file_exists(public_path($data->file))) {
            unlink($data->file);
        }
        $data->delete();
        return response()->json(['message' => 'deleted', 'status' => 200]);
    }

    public function priority($id)
    {
        $ad = Ad::select('id', 'priority')->find($id);
        $ad->priority = (int) !$ad->priority;
        $ad->save();
        return response()->json(['message' => 'updated', 'status' => 204]);
    }
}
