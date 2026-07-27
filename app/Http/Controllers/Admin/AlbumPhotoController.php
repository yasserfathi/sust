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
            }
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
        if ($request->hasFile('img') && $request->file('img')->isValid()) {
            try {
                $manager = new ImageManager(new Driver());
                $img = $request->file('img');

                $rand = hexdec(uniqid());
                $imagename = $rand . '.' . $img->getClientOriginalExtension();
                $imagename_thumb = $rand . '_thumb.' . $img->getClientOriginalExtension();

                if (Str::length($img_path) > 0 && file_exists(public_path($img_path))) {
                    unlink($img_path);
                }

                if (Str::length($thumb_path) > 0 && file_exists(public_path($thumb_path))) {
                    unlink($thumb_path);
                }

                $img->storeAs('images/albums', $imagename, 'public');
                $manager->read($img)->scale(width: 300)->save(public_path('images/albums_thumbnail/' . $imagename_thumb));

                $img_path = 'images/albums/' . $imagename;
                $thumb_path = 'images/albums_thumbnail/' . $imagename_thumb;
            } catch (Exception $e) {
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
        $data = albumPhoto::findOrFail($albumPhoto->id);

        if (Str::length($data->img) > 0 && file_exists(public_path($data->img))) {
            unlink($data->img);
        }

        if (Str::length($data->thumb_img) > 0 && file_exists(public_path($data->thumb_img))) {
            unlink($data->thumb_img);
        }

        $data->delete();
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
}
