<?php

namespace App\Http\Controllers\Admin;

use App\Models\AlbumPhoto;
use App\Models\CollegeGallery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CollegeGalleryRequest;
use Illuminate\Support\Str;

class CollegeGalleryController extends Controller
{
    public function index(Request $request)
    {
        $itemsPerPage = (int) $request->get('items', 15);
        $search = $request->get('search', '');

        $query = CollegeGallery::select('id', 'college_id', 'photos')
            ->with('college:id,name')
            ->whereHas('college', function ($q) use ($search) {
                $q->where('user_id', 1);
                if (!empty($search)) {
                    $q->where('name', 'like', '%' . $search . '%');
                }
            })
            ->orderBy('college_id', 'DESC');

        if ($request->filled(['orderby', 'ascend'])) {
            $orderby = $request->get('orderby');
            $ascend  = strtolower($request->get('ascend')) === 'asc' ? 'asc' : 'desc';
            $query->orderBy($orderby, $ascend);
        }

        if ($itemsPerPage === 0) {
            $itemsPerPage = $query->count();
        }

        return response()->json([
            'result' => $query->paginate($itemsPerPage)
        ], 200);
    }
    
    public function store(CollegeGalleryRequest $request)
    {
         $validator = $request->validated();
         CollegeGallery::create( array_merge(
            $validator,
            ['user_id' => Auth::user()->id]
        ));
        return response()->json(['message' => 'created', 'status' => 201]);
    }
    public function show($id)
    {
        $data = CollegeGallery::select('photos')->where('college_id', $id)->first();
        $photoIds = explode(',', $data->photos);
        $albumPhotos = AlbumPhoto::whereIn('id', $photoIds)->get();

        $formattedPhotos = $albumPhotos->map(function ($photo) {
            return [
                'id' => $photo->id,
                'thumb_photo' => $photo->thumb_img,
                'photo' => $photo->img,
                'title' => $photo->title,
            ];
        });
        return response()->json([
            'album_photos' => $formattedPhotos
        ], 200);
    }

    public function update(Request $request, CollegeGallery $collegeGallery)
    {
        $record = CollegeGallery::find($collegeGallery->id);
        $record->photos = $request->photos;
        $record->user_id = Auth::user()->id;

        if ($record->isDirty()) { // if record data changed
            $record->save();
            return response()->json(['message' => 'updated', 'status' => 204]);
        }
        return response()->json(['message' => 'not changed', 'status' => 304]);
    }

}
