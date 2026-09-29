<?php

namespace App\Http\Controllers\Admin;

use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AlbumRequest;
use Illuminate\Support\Facades\Http;

class AlbumController extends Controller
{
    public function index(Request $request)
    {
        $itemsPerPage = (int) htmlspecialchars($request->get('items') ?? 15);
        if ($itemsPerPage <= 0) {
            $itemsPerPage = 1000;
        }
        $search = htmlspecialchars($request->get('search') ?? '');
        $search = trim(preg_replace('/[+\-><\(\)~*\"@]+/', ' ', $search));

        $query = Album::select('albums.id', 'albums.college_id', 'albums.title', 'albums.title_en', 'albums.description', 'albums.active')
            ->with('college:id,name');

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
                $q->whereRaw('MATCH(albums.title, albums.title_en) AGAINST(? IN BOOLEAN MODE)', [implode(' ', array_map(function($w) { $w = trim(preg_replace('/[+\-\><\(\)~*"@]+/', '', $w)); if (!$w) return ''; $prefixes = ['', 'ال', 'وال', 'بال', 'فال', 'لل', 'كال']; $group = []; foreach($prefixes as $p) { $group[] = $p . $w . '*'; } return '+(' . implode(' ', $group) . ')'; }, explode(' ', $search)))])
                  ->orWhereHas('college', function ($cq) use ($search) {
                      $cq->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        if ($request->has('orderby') && $request->has('ascend')) {
            $orderBy = $request->get('orderby');
            $sortDirection = ($request->get('ascend') === 'true' || $request->get('ascend') === 'asc' || $request->get('ascend') == '1') ? 'asc' : 'desc';

            if ($orderBy === 'college.name') {
                $query->join('colleges', 'colleges.id', '=', 'albums.college_id')
                      ->orderBy('colleges.name', $sortDirection);
            } else {
                $query->orderBy('albums.' . ltrim($orderBy, 'albums.'), $sortDirection);
            }
        } else {
            $query->orderBy('albums.id', 'desc');
        }

        return response()->json(['result' => $query->paginate($itemsPerPage)], 200);
    }

    public function show(Album $album)
    {
        $data = Album::with('photos')->select('id','college_id','title','title_en','description','keywords','active')->where('id',$album->id)->first();
        return response()->json(['result' => $data, 'status' => 200]);
    }

    public function store(AlbumRequest $request)
	{
        $validator = $request->validated();

		$active = 0;
        if (isset($request->active) && $request->active == 1) {
            $active = 1;
        }

	    $album = Album::create(array_merge(
            $validator,
		    ['user_id' => Auth::user()->id],
			['active' => $active],
		));

		return response()->json(['message' => 'created', 'album_id' => $album->id,'status' => 201]);
	}

     public function update(AlbumRequest $request, Album $album)
    {
        $active = 0;
        if (isset($request->active) && $request->active == 1) {
            $active = 1;
        }

        $record = Album::find($album->id);
        $record->college_id = $request->college_id;
        $record->title = $request->title;
        $record->title_en = $request->title_en;
        $record->description = $request->description;
        $record->description = $request->description;
        $record->active = $active;
        $record->user_id = Auth::user()->id;

        if ($record->isDirty()) { // if record data changed
            $record->save();
            return response()->json(['message' => 'updated', 'status' => 204]);
        }
        return response()->json(['message' => 'not changed', 'status' => 304]);
    }

	public function destroy(Album $album)
    {
		$album->delete();
		return response()->json(['message' => 'deleted', 'status' => 200]);
    }

    public function list(Request $request)
	{
		$data = Album::select('id','title')
                ->where('college_id',$request->college_id)
                ->where('active',DB::raw('1'))
                ->get();
        return response()->json([
            'albums' => $data
        ], 200);
	}
}
