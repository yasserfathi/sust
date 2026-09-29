<?php

namespace App\Http\Controllers\Admin;

use App\Models\College;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CollegeRequest;
use Illuminate\Support\Str;

class CollegesController extends Controller
{
    public function index(Request $request)
    {
        $itemsPerPage = htmlspecialchars($request->get('items') ?? 15);
        if ($itemsPerPage <= 0) {
            $itemsPerPage = 1000;
        }
        $search = htmlspecialchars($request->get('search') ?? '');
        $search = trim(preg_replace('/[+\-><\(\)~*\"@]+/', ' ', $search));

        $data = College::select('id', 'name', 'name_en', 'slug', 'college_type', 'active');

        if (!empty($search)) {
            $data->whereRaw('MATCH(name, name_en) AGAINST(? IN BOOLEAN MODE)', [implode(' ', array_map(function($w) { $w = trim(preg_replace('/[+\-\><\(\)~*"@]+/', '', $w)); if (!$w) return ''; $prefixes = ['', 'ال', 'وال', 'بال', 'فال', 'لل', 'كال']; $group = []; foreach($prefixes as $p) { $group[] = $p . $w . '*'; } return '+(' . implode(' ', $group) . ')'; }, explode(' ', $search)))])
                ->orWhere('college_type', 'like', '%' . $search . '%');
        }

        if (!$request->exists('orderby') && !empty($request->get('ascend'))) {
            $data->orderBy($request->get('orderby'), in_array(strtolower(trim($request->get('ascend') ?? '')), ['asc', 'true', '1']) ? 'asc' : 'desc');
        } else {
            $data->orderByDesc('id');
        }

        $resource = $data->paginate((int) $itemsPerPage);

        return response()->json([
            'result' => $resource->setCollection($resource->getCollection()->makeVisible('id'))
        ], 200);
    }

    public function show($id)
    {
        $data = College::select('name', 'name_en', 'slug', 'college_type', 'logo', 'logo_en', 'banner', 'active')->where('id', $id)->first();

        return response()->json(['result' => $data, 'status' => 200]);
    }

    public function store(CollegeRequest $request)
    {
        $validator = $request->validated();
        $logo = $logo_path = '';
        $logo_en = $logo_en_path = '';
        $banner_path = '';
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            try {
                $logo = $request->file('logo');
                $rand = hexdec(uniqid());
                $imagename = $rand . '.' . $logo->getClientOriginalExtension();

                $logo->storeAs('images/logos', $imagename, 'public');

                $logo_path = 'images/logos/' . $imagename;
            } catch (Exception $e) {
                return response()->json(['message' => 'Error processing file: ' . $e->getMessage(), 'status' => 500], 500);
            }
        }
        if ($request->hasFile('logo_en') && $request->file('logo_en')->isValid()) {
            try {
                $logo_en = $request->file('logo_en');
                $rand = hexdec(uniqid());
                $imagename = $rand . '.' . $logo_en->getClientOriginalExtension();

                $logo_en->storeAs('images/logos', $imagename, 'public');

                $logo_en_path = 'images/logos/' . $imagename;
            } catch (Exception $e) {
                return response()->json(['message' => 'Error processing file: ' . $e->getMessage(), 'status' => 500], 500);
            }
        }
        if ($request->hasFile('banner') && $request->file('banner')->isValid()) {
            try {
                $banner = $request->file('banner');
                $rand = hexdec(uniqid());
                $imagename = $rand . '.' . $banner->getClientOriginalExtension();

                $banner->storeAs('images/banners', $imagename, 'public');

                $banner_path = 'images/banners/' . $imagename;
            } catch (Exception $e) {
                return response()->json(['message' => 'Error processing file: ' . $e->getMessage(), 'status' => 500], 500);
            }
        }
        $active = 0;
        if (isset($request->active) && (int) $request->active == 1) {
            $active = 1;
        }
        $slug = Str::slug($request->name_en);
        College::create(array_merge(
            $validator,
            ['user_id' => Auth::user()->id],
            ['slug' => $slug],
            ['active' => $active],
            ['logo' => $logo_path],
            ['logo_en' => $logo_en_path],
            ['banner' => $banner_path],
        ));
        return response()->json(['message' => 'created', 'status' => 201]);
    }

    public function update(CollegeRequest $request, string $id)
    {
        $record = college::findOrFail($id);
        $active = 0;
        if (isset($request->active) && $request->active == 1) {
            $active = 1;
        }

        $logo_path = $record->logo;
        if ($request->hasFile('logo')) {
            if ($request->file('logo')->isValid()) {
                try {
                    $logo = $request->file('logo');
                    $rand = hexdec(uniqid());
                    $Logoname = $rand . '.' . $logo->getClientOriginalExtension();
                    $logo_path = $logo->storeAs('images/logos', $Logoname, 'public');
                    if (Str::length($record->logo) > 0 && file_exists(public_path($record->logo))) {
                        unlink(public_path($record->logo));
                    }
                } catch (Exception $e) {
                return response()->json(['message' => 'Error processing file: ' . $e->getMessage(), 'status' => 500], 500);
            }
            }
        }

        $logo_en_path = $record->logo_en;
        if ($request->hasFile('logo_en')) {
            if ($request->file('logo_en')->isValid()) {
                try {
                    $logo_en = $request->file('logo_en');
                    $rand = hexdec(uniqid());
                    $LogoEnname = $rand . '.' . $logo_en->getClientOriginalExtension();
                    $logo_en_path = $logo_en->storeAs('images/logos', $LogoEnname, 'public');
                    if (Str::length($record->logo_en) > 0 && file_exists(public_path($record->logo_en))) {
                        unlink(public_path($record->logo_en));
                    }
                } catch (Exception $e) {
                return response()->json(['message' => 'Error processing file: ' . $e->getMessage(), 'status' => 500], 500);
            }
            }
        }

        $banner_path = $record->banner;
        if ($request->hasFile('banner')) {
            if ($request->file('banner')->isValid()) {
                try {
                    $banner = $request->file('banner');
                    $rand = hexdec(uniqid());
                    $Bannername = $rand . '.' . $banner->getClientOriginalExtension();
                    $banner_path = $banner->storeAs('images/banners', $Bannername, 'public');
                    if (Str::length($record->banner) > 0 && file_exists(public_path($record->banner))) {
                        unlink(public_path($record->banner));
                    }
                } catch (Exception $e) {
                return response()->json(['message' => 'Error processing file: ' . $e->getMessage(), 'status' => 500], 500);
            }
            }
        }

        $record->name = $request->name;
        $record->name_en = $request->name_en;
        $record->slug = Str::slug($request->name_en);
        $record->college_type = $request->college_type;
        $record->active = $active;
        $record->logo = $logo_path;
        $record->banner = $banner_path;
        $record->logo_en = $logo_en_path;
        $record->user_id = Auth::user()->id;

        if ($record->isDirty()) { // if record data changed
            $record->save();
            return response()->json(['message' => 'updated', 'status' => 204]);
        }
        return response()->json(['message' => 'not changed', 'status' => 304]);
    }

    public function destroy(string $id)
    {
        College::find($id)->delete();
        return response()->json(['message' => 'deleted', 'status' => 200]);
    }

    public function list()
    {
        $user = Auth::user();
        $query = College::select('id', 'name')->where('active', '1');

        if ($user && $user->role != 1) {
            if ($user->is_college_rep) {
                $collegeId = $user->staff_latest_by_id?->department?->college_id;
                if ($collegeId) {
                    $query->where('id', $collegeId);
                } else {
                    $query->whereRaw('1 = 0');
                }
            } else {
                // If they are not a rep, fallback to their assigned colleges (or whatever previous logic they had)
                // The previous logic was:
                // $query->whereHas('departments.staff', function ($q) use ($user) {
                //     $q->where('user_id', $user->id);
                // });
                // But NewsController used `$query->where('user_id', $user->id);`
                // Let's stick to what NewsController does for `category`/`user_id` on colleges.
                $query->where('user_id', $user->id);
            }
        }

        $data = $query->get()->makeVisible(['id']);
        return response()->json([
            'colleges' => $data
        ], 200);
    }
}
