<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Exception;
use App\Models\News;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\NewsEnRequest;
use Illuminate\Support\Str;

class NewsEnController extends Controller
{
    public function index(Request $request)
    {
        $itemsPerPage = htmlspecialchars($request->get('items') ?? 15);
        if ($itemsPerPage <= 0) {
            $itemsPerPage = 1000;
        }
        $search = htmlspecialchars($request->get('search') ?? '');
        $search = trim(preg_replace('/[+\-><\(\)~*\"@]+/', ' ', $search));

        $query = News::select('id', 'college_id', 'news_date', 'active', 'priority', 'title')
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
            ->where('lang', 2);

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

    public function show(News $news_en)
    {
        $data = News::select('id', 'college_id', 'title', 'slug', 'news_date', 'active', 'detail_portion', 'detail', 'file')
            ->with(['college:id,name', 'photos:id,thumb_img as thumb_photo,img as photo,title'])
            ->where('id', $news_en->id)
            ->first();

        return response()->json(['result' => $data, 'status' => 200]);
    }

    public function store(NewsEnRequest $request)
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

            $news_en = News::create(array_merge(
                $validator,
                [
                    'slug' => Str::of($request->title)->trim()->replace(' ', '-')->value()
                ],
                ['auth_id' => Auth::user()->id],
                ['active' => $active],
                ['file' => $file_path],
                ['lang' => 2],
            ));

            if ($request->has('photos')) {
                $photos = $request->photos;
                if (is_string($photos)) {
                    $photos = explode(',', $photos);
                }
                $news_en->photos()->sync($photos);
            }

            return response()->json(['message' => 'created', 'status' => 201]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()], 500);
        }
    }

    public function update(NewsEnRequest $request, News $news_en)
    {
        try {
            $file_path = $news_en->file;
            if ($request->hasFile('file') && $request->file('file')->isValid()) {
                try {
                    $file = $request->file('file');
                    $rand = hexdec(uniqid());
                    $filename = $rand . '.' . $file->getClientOriginalExtension();
                    $file_path = $file->storeAs('files', $filename, 'public');
                    if (Str::length($news_en->file) > 0 && file_exists(public_path($news_en->file))) {
                        unlink(public_path($news_en->file));
                    }
                } catch (Exception $e) {
                    return response()->json(['message' => 'Error processing file: ' . $e->getMessage(), 'status' => 500], 500);
                }
            }

            $active = (isset($request->active) && $request->active == 1) ? 1 : 0;

            $news_en->title = $request->title;
            $news_en->slug = Str::of($request->title)->trim()->replace(' ', '-')->value();
            $news_en->college_id = $request->college_id;
            $news_en->news_date = Carbon::parse($request->news_date);
            $news_en->active = $active;
            if ($active == 0) {
                $news_en->priority = 0;
            }
            $news_en->keywords = $request->keywords;
            $news_en->detail_portion = $request->detail_portion;
            $news_en->detail = $request->detail;
            $news_en->file = $file_path;
            $news_en->auth_id = Auth::user()->id;

            if ($news_en->isDirty()) {
                $news_en->save();
            }

            if ($request->has('photos')) {
                $photos = $request->photos;
                if (is_string($photos)) {
                    $photos = explode(',', $photos);
                }
                $news_en->photos()->sync($photos);
            }

            return response()->json(['message' => 'updated', 'status' => 204]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()], 500);
        }
    }

    public function destroy(News $news_en)
    {
        if (Str::length($news_en->file) > 0 && file_exists(public_path($news_en->file))) {
            unlink(public_path($news_en->file));
        }
        $news_en->delete();
        return response()->json(['message' => 'deleted', 'status' => 200]);
    }

    public function priority($id)
    {
        $news_en = News::select('id', 'priority', 'lang')->find($id);

        if (!$news_en) {
            return response()->json(['message' => 'Not found', 'status' => 404], 404);
        }
        
        if (!$news_en->priority) {
            $count = News::where('lang', $news_en->lang)->where('priority', 1)->count();
            if ($count >= 3) {
                return response()->json(['message' => 'Featured priority news cannot exceed 3 items', 'status' => 400]);
            }
        }

        $news_en->priority = (int) !$news_en->priority;
        $news_en->save();
        return response()->json(['message' => 'updated', 'status' => 204]);
    }
}
