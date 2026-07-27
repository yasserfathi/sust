<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Http\Requests\PageRequest;
use Illuminate\Support\Str;

class PageController extends Controller
{

    protected $page_slug;

    public function __construct(Request $request)
    {
        $this->page_slug = $request->segment(3);
    }

    public function index(Request $request)
    {
        // Validate and sanitize items per page
        $itemsPerPage = (int)htmlspecialchars($request->get('items', 15));
        $itemsPerPage = max(0, $itemsPerPage); // Ensure it's not negative

        // Sanitize search input
        $search = htmlspecialchars($request->get('search', ''));

        // Base query
        $query = Page::select(
            'id',
            'title',
            'college_id',
            DB::raw('COALESCE(category, "-") AS category'),
            DB::raw('(CASE WHEN lang = 1 THEN "اللغة العربية" ELSE "اللغة الانجليزية" END) AS lang')
        )
            ->with('college:id,name')
            ->whereHas('college', function ($q) use ($search) {
                $q->where('user_id', 1);
                if (!empty($search)) {
                    $q->where('name', 'like', '%' . $search . '%');
                }
            })
            ->where('slug', $this->page_slug);

        // Sorting
        $orderBy = $request->get('orderby');
        $ascend = $request->get('ascend');

        if ($orderBy && $ascend) {
            $query->orderBy($orderBy, $ascend);
        } else {
            $query->orderBy('id', 'desc');
        }

        // Pagination
        $result = $query->paginate($itemsPerPage);

        return response()->json(['result' => $result], 200);
    }

    public function show($slug, $id)
    {
        $edit_data = Page::select('college_id', 'category', 'title', 'lang', 'detail_portion', 'detail', 'keywords', 'img', 'thumb_img', 'file')
            ->where('id', $id)
            ->where('slug', $slug)
            ->first();
        return response()->json(['result' => $edit_data, 'status' => 200]);
    }

    public function store(PageRequest $request)
    {
        $validator = $request->validated();
        $img_path = '';
        $thumb_path = '';
        if ($request->hasFile('img') && $request->file('img')->isValid()) {
            try {
                $manager = new ImageManager(new Driver());
                $img = $request->file('img');
                $rand = hexdec(uniqid());
                $imagename = $rand . '.' . $img->getClientOriginalExtension();
                $imagename_thumb = $rand . '_thumb.' . $img->getClientOriginalExtension();

                $img->storeAs('images/pages', $imagename, 'public');
                $manager->read($img)->scale(width: 300)->save(public_path('images/pages_thumbnail/' . $imagename_thumb));

                $img_path = 'images/pages/' . $imagename;
                $thumb_path = 'images/pages_thumbnail/' . $imagename_thumb;
            } catch (Exception $e) {
            }
        }

        $file_path = '';
        if ($request->hasFile('file')) {
            if ($request->file('file')->isValid()) {
                try {
                    $file = $request->file('file');
                    $filename = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension();
                    $file_path = $file->storeAs('files', $filename, 'public');
                } catch (Exception $e) {
                }
            }
        }

        Page::create(array_merge(
            $validator,
            ['slug' => $this->page_slug],
            ['auth_id' => Auth::user()->id],
            ['img' => $img_path],
            ['file' => $file_path],
            ['thumb_img' => $thumb_path],
        ));
        return response()->json(['message' => 'created', 'status' => 201]);
    }

    public function update(PageRequest $request, $slug, string $id)
    {
        $record = Page::where('slug', $slug)->find($id);
        if (!$record) {
            return response()->json(['message' => 'الصفحة غير موجودة أو تم حذفها', 'status' => 404], 404);
        }
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
                        unlink($img_path);
                    }

                    if (Str::length($thumb_path) > 0 && file_exists(public_path($thumb_path))) {
                        unlink($thumb_path);
                    }

                    $img->storeAs('images/pages', $imagename, 'public');
                    $manager->read($img)->scale(width: 300)->save(public_path('images/pages_thumbnail/' . $imagename_thumb));

                    $img_path = 'images/pages/' . $imagename;
                    $thumb_path = 'images/pages_thumbnail/' . $imagename_thumb;
                } catch (Exception $e) {
                }
            }
        }

        $file_path = $record->file;
        if ($request->hasFile('file')) {
            if ($request->file('file')->isValid()) {
                try {
                    $file = $request->file('file');
                    $filename = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension();
                    if ($file_path != '') {
                        Storage::delete($file_path);
                    }
                    $file_path = $file->storeAs('files', $filename, 'public');
                } catch (Exception $e) {
                }
            }
        }

        $record->college_id = $request->college_id;
        $record->title = $request->title;
        $record->category = $request->category;
        $record->lang = $request->lang;
        $record->keywords = $request->keywords;
        $record->img = $img_path;
        $record->thumb_img = $thumb_path;
        $record->file = $file_path;
        $record->detail_portion = $request->detail_portion;
        $record->detail = $request->detail;
        $record->auth_id = Auth::user()->id;

        if ($record->isDirty()) { // if record data changed
            $record->save();
            return response()->json(['message' => 'updated', 'status' => 204]);
        } else {
            return response()->json(['message' => 'not changed', 'status' => 304]);
        }
    }

    public function destroy($slug, Page $page)
    {
        $data = Page::where('slug', $slug)->find($page->id);
        if (!$data) {
            return response()->json(['message' => 'الصفحة غير موجودة أو تم حذفها مسبقاً', 'status' => 404], 404);
        }

        if (Str::length($data->img) > 0 && file_exists(public_path($data->img))) {
            unlink($data->img);
        }

        if (Str::length($data->thumb_img) > 0 && file_exists(public_path($data->thumb_img))) {
            unlink($data->thumb_img);
        }

        if (Str::length($data->file) > 0 && file_exists(public_path($data->file))) {
            unlink($data->file);
        }

        $data->delete();
        return response()->json(['message' => 'deleted', 'status' => 200]);
    }

    public function categoryList()
    {
        $categories = Page::select('category')
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category');
            
        return response()->json(['categories' => $categories], 200);
    }
}
