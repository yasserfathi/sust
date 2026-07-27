<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Exception;
use App\Models\News;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\NewsRequest;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $itemsPerPage = htmlspecialchars($request->get('items') ?? 15);
        if ($itemsPerPage < 0) {
            $itemsPerPage = 0;
        }
        $search = htmlspecialchars($request->get('search') ?? '');


        $query = News::query()
            ->select([
                'id',
                'college_id',
                'news_date',
                'active',
                'priority',
                'title'
            ])
            ->with('college:id,name')
            ->whereHas('college', function ($q) {
                $q->where('user_id', Auth::id());
            });

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('news_date', 'like', '%' . $search . '%')
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

    public function show(News $news)
    {
        $data = News::select('id', 'college_id', 'title', 'slug', 'news_date', 'active', 'detail_portion', 'detail', 'file')
            ->with(['college:id,name', 'photos:id,thumb_img as thumb_photo,img as photo,title'])
            ->where('id', $news->id)
            ->first();

        return response()->json(['result' => $data, 'status' => 200]);
    }

    public function store(NewsRequest $request)
    {
        $request->news_date = Carbon::parse($request->news_date);
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
        $news = News::create(array_merge(
            $validator,
            [
                'slug' => Str::of($request->title)
                    ->trim()
                    ->replace(' ', '-')
                    ->value()
            ],
            ['auth_id' => Auth::user()->id],
            ['active' => $active],
            ['file' => $file_path],
        ));

        if ($request->has('photos')) {
            $photos = $request->photos;
            if (is_string($photos)) {
                $photos = explode(',', $photos);
            }
            $news->photos()->sync($photos);
        }

        return response()->json(['message' => 'created', 'status' => 201]);
    }

    public function update(NewsRequest $request, News $news)
    {
        $record = News::find($news->id);
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
                } catch (Exception $e) {
                }
            }
        }
        $active = 0;
        if (isset($request->active) && $request->active == 1) {
            $active = 1;
        }
        $record->title = $request->title;
        $record->slug = Str::of($request->title)
            ->trim()
            ->replace(' ', '-')
            ->value();
        $record->college_id = $request->college_id;
        $record->news_date = Carbon::parse($request->news_date);
        $record->active = $active;
        if ($active == 0) {
            $record->priority = 0;
        }
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

    public function destroy(News $news)
    {
        $data = News::find($news->id);
        if (Str::length($data->file) > 0 && file_exists(public_path($data->file))) {
            unlink($data->file);
        }
        $data->delete();
        return response()->json(['message' => 'deleted', 'status' => 200]);
    }

    public function priority($id)
    {
        $news = News::select('id', 'priority')->find($id);
        $news->priority = (int) !$news->priority;
        $news->save();
        return response()->json(['message' => 'updated', 'status' => 204]);
    }
}
