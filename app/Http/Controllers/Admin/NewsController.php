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
        if ($itemsPerPage <= 0) {
            $itemsPerPage = 1000;
        }
        $search = htmlspecialchars($request->get('search') ?? '');
        $search = trim(preg_replace('/[+\-><\(\)~*\"@]+/', ' ', $search));

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
                    ->orWhere('news_date', 'like', '%' . $search . '%')
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
        try {
            $request->news_date = Carbon::parse($request->news_date);
            $validator = $request->validated();

            $active = (isset($request->active) && $request->active == 1) ? 1 : 0;

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
                ['lang' => 1],
            ));

            if ($request->has('photos')) {
                $photos = $request->photos;
                if (is_string($photos)) {
                    $photos = explode(',', $photos);
                }
                $news->photos()->sync($photos);
            }

            return response()->json(['message' => 'created', 'status' => 201]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()], 500);
        }
    }

    public function update(NewsRequest $request, News $news)
    {
        try {
            $file_path = $news->file;
            if ($request->hasFile('file')) {
                if ($request->file('file')->isValid()) {
                    try {
                        $file = $request->file('file');
                        $rand = hexdec(uniqid());
                        $filename = $rand . '.' . $file->getClientOriginalExtension();
                        $file_path = $file->storeAs('files', $filename, 'public');
                        if (Str::length($news->file) > 0 && file_exists(public_path($news->file))) {
                            unlink(public_path($news->file));
                        }
                    } catch (Exception $e) {
                        return response()->json(['message' => 'Error processing file: ' . $e->getMessage(), 'status' => 500], 500);
                    }
                }
            }

            $active = (isset($request->active) && $request->active == 1) ? 1 : 0;

            $news->title = $request->title;
            $news->slug = Str::of($request->title)
                ->trim()
                ->replace(' ', '-')
                ->value();
            $news->college_id = $request->college_id;
            $news->news_date = Carbon::parse($request->news_date);
            $news->active = $active;
            if ($active == 0) {
                $news->priority = 0;
            }
            $news->keywords = $request->keywords;
            $news->detail_portion = $request->detail_portion;
            $news->detail = $request->detail;
            $news->file = $file_path;
            $news->auth_id = Auth::user()->id;

            if ($news->isDirty()) {
                $news->save();
            }

            if ($request->has('photos')) {
                $photos = $request->photos;
                if (is_string($photos)) {
                    $photos = explode(',', $photos);
                }
                $news->photos()->sync($photos);
            }

            return response()->json(['message' => 'updated', 'status' => 204]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()], 500);
        }
    }

    public function destroy(News $news)
    {
        if (Str::length($news->file) > 0 && file_exists(public_path($news->file))) {
            unlink(public_path($news->file));
        }
        $news->delete();
        return response()->json(['message' => 'deleted', 'status' => 200]);
    }

    public function priority($id)
    {
        $news = News::select('id', 'priority', 'lang')->find($id);

        if (!$news) {
            return response()->json(['message' => 'غير موجود', 'status' => 404], 404);
        }
        
        if (!$news->priority) {
            $count = News::where('lang', $news->lang)->where('priority', 1)->count();
            if ($count >= 3) {
                return response()->json(['message' => 'الأخبار المميزة بأولوية النشر عددها 3', 'status' => 400]);
            }
        }

        $news->priority = (int) !$news->priority;
        $news->save();
        return response()->json(['message' => 'updated', 'status' => 204]);
    }
}
