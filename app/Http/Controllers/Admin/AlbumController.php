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
        $itemsPerPage = htmlspecialchars($request->get('items') ?? 15);
        if($itemsPerPage < 0 ){
            $itemsPerPage = 0;
        }
        $search = htmlspecialchars($request->get('search') ?? '');

        //collection serach News Title
        $result_news_title = Album::select('id','college_id','title','title_en','description','active');

        if(!empty($search)){
            $result_news_title->where('title', 'like', '%'.$search.'%')
                ->orWhere('title_en', 'like', '%'.$search.'%');
        }

       $result_news_title->with('college:id,name')->whereHas('college', function ($query) use ($search) {
                        $query->where('user_id',1);
                  });

        // ===================================

        //collection serach College Name
        $result_college_name = Album::select('id','college_id','title','title_en','description','active');

        $result_college_name->with('college:id,name')->whereHas('college', function ($query) use ($search) {
                        $query->where('user_id',1);
                        if(!empty($search)){
                            $query->where('name', 'like', '%'.$search.'%');
                        }
                  });

        $all = $result_news_title->get()->merge($result_college_name->get());

        if($request->exists('orderby') && $request->exists('ascend')){
            $all = $all->sortBy([[$request->get('orderby'),$request->get('ascend')]]);
        }

        return response()->json(['result' => $all->paginate((int)$itemsPerPage)], 200);

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
		// $album::find($album->id)->delete();
		// return response()->json(['message' => 'deleted', 'status' => 200]);
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
