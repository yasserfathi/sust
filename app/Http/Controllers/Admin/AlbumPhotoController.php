<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlbumPhoto;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Http\Requests\AlbumPhotoRequest;
use Illuminate\Support\Str;

class AlbumPhotoController extends Controller
{
    public function index()
    {
        //return view('admin.album-photos')->with('colleges', $colleges);
    }

    public function show(AlbumPhoto $albumPhoto)
    {
        $data = AlbumPhoto::where('id', $albumPhoto->id)->first();
        return response()->json(['result' => $data, 'status' => 200]);
    }

    public function store(AlbumPhotoRequest $request)
    {
        $validator = $request->validated();
        $img_path = $thumb_path = '';

        if ($request->hasFile('img') && $request->file('img')->isValid()) {
            try {
                $manager = new ImageManager(new Driver());
                $img = $request->file('img');
                $rand = hexdec(uniqid());
                $imagename = $rand . '.' . $img->getClientOriginalExtension();
                $imagename_thumb = $rand . '_thumb.' . $img->getClientOriginalExtension();

                $img->storeAs('images/albums', $imagename, 'public');
                $manager->read($img)->scale(width: 300)->save(public_path('images/albums_thumbnail/' . $imagename_thumb));

                $img_path = 'images/albums/' . $imagename;
                $thumb_path = 'images/albums_thumbnail/' . $imagename_thumb;
            } catch (Exception $e) {
                return response()->json(['message' => 'Error processing file: ' . $e->getMessage(), 'status' => 500], 500);
            }
        } else {
            return response()->json(['message' => ['img' => 'الصورة مطلوبة'], 'status' => 409], 200);
        }

        AlbumPhoto::create(array_merge(
            $validator,
            ['user_id' => Auth::user()->id],
            ['img' => $img_path],
            ['thumb_img' => $thumb_path],
        ));

        return response()->json(['message' => 'created', 'status' => 201]);
    }

    public function update(AlbumPhotoRequest $request, AlbumPhoto $albumPhoto)
    {
        $record = AlbumPhoto::findOrFail($albumPhoto->id);

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

                    $manager->read($img)->scale(width: 300)->save(public_path('images/albums_thumbnail/' . $imagename_thumb));
                    $img->storeAs('images/albums', $imagename, 'public');

                    $img_path = 'images/albums/' . $imagename;
                    $thumb_path = 'images/albums_thumbnail/' . $imagename_thumb;
                } catch (Exception $e) {
                    return response()->json(['message' => 'Error processing file: ' . $e->getMessage(), 'status' => 500], 500);
                }
            } else {
                return response()->json(['message' => ['img' => 'عفوا، حجم الصورة يتجاوز الحد المسموح به أو الملف غير صالح'], 'status' => 409], 200);
            }
        }

        $record->title = $request->title;
        $record->title_en = $request->title_en;
        $record->album_id = $request->album_id;
        $record->img = $img_path;
        $record->thumb_img = $thumb_path;
        $record->user_id = Auth::user()->id;
        if ($record->isDirty()) { // if record data changed
            $record->save();
            return response()->json(['message' => 'updated', 'status' => 204]);
        } else {
            return response()->json(['message' => 'not changed', 'status' => 304]);
        }
    }

    public function destroy(AlbumPhoto $albumPhoto)
    {
        if (Str::length($albumPhoto->img) > 0 && file_exists(public_path($albumPhoto->img))) {
            unlink(public_path($albumPhoto->img));
        }

        if (Str::length($albumPhoto->thumb_img) > 0 && file_exists(public_path($albumPhoto->thumb_img))) {
            unlink(public_path($albumPhoto->thumb_img));
        }

        $albumPhoto->delete();
        return response()->json(['message' => 'deleted', 'status' => 200]);
    }

    public function list(Request $request)
    {
        $data = AlbumPhoto::select('id', 'thumb_img as thumb_photo', 'img as photo', 'title')
            ->where('album_id', $request->album_id)
            ->get();
        return response()->json([
            'album_photos' => $data
        ], 200);
    }

    public function list_photos($id)
    {
        $data = AlbumPhoto::select('id', 'thumb_img as thumb_photo', 'title')
            ->where('album_id', $id)
            ->get();
        return response()->json([
            'album_photos' => $data
        ], 200);
    }

    public function toggleActive(Request $request, $id)
    {
        $photo = AlbumPhoto::findOrFail($id);
        $albumId = $photo->album_id;

        $newStatus = $request->boolean('is_active');

        if ($newStatus) {
            $currentActiveCount = AlbumPhoto::where('album_id', $albumId)
                ->where('is_active', true)
                ->where('id', '!=', $id)
                ->count();

            if ($currentActiveCount >= 5) {
                return response()->json([
                    'message' => 'يمكنك اختيار 5 صور كحد أقصى لعرضها في الكلية',
                    'status' => 422
                ], 422);
            }
        }

        $photo->is_active = $newStatus;
        $photo->save();

        return response()->json([
            'message' => 'تم التحديث بنجاح',
            'is_active' => $photo->is_active,
            'status' => 200
        ], 200);
    }
}
