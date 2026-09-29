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
        $search = trim(preg_replace('/[+\-><\(\)~*\"@]+/', ' ', $search));

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
                $authUser = Auth::user();
                if ($authUser->role != 1) {
                    if ($authUser->is_college_rep) {
                        $collegeId = $authUser->staff_latest_by_id?->department?->college_id;
                        $q->where('id', $collegeId);
                    } else {
                        $q->where('user_id', $authUser->id);
                    }
                }
                if (!empty($search)) {
                    $q->whereRaw('MATCH(name) AGAINST(? IN BOOLEAN MODE)', [implode(' ', array_map(function($w) { $w = trim(preg_replace('/[+\-\><\(\)~*"@]+/', '', $w)); if (!$w) return ''; $prefixes = ['', 'ال', 'وال', 'بال', 'فال', 'لل', 'كال']; $group = []; foreach($prefixes as $p) { $group[] = $p . $w . '*'; } return '+(' . implode(' ', $group) . ')'; }, explode(' ', $search)))]);
                }
            })
            ->where('slug', $this->page_slug);

        // Sorting
        $orderBy = $request->get('orderby');
        $ascend = in_array(strtolower(trim($request->get('ascend') ?? '')), ['asc', 'true', '1']) ? 'asc' : 'desc';

        if ($orderBy && $ascend) {
            $query->orderBy($orderBy, $ascend);
        } else {
            $query->orderByDesc('id');
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
                $manager->read($img)->scale(width: 300)->save(public_path('images/pages_thumbnail/' . $imagename_thumb));
                $img->storeAs('images/pages', $imagename, 'public');

                $img_path = 'images/pages/' . $imagename;
                $thumb_path = 'images/pages_thumbnail/' . $imagename_thumb;
            } catch (Exception $e) {
                return response()->json(['message' => 'Error processing file: ' . $e->getMessage(), 'status' => 500], 500);
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
                return response()->json(['message' => 'Error processing file: ' . $e->getMessage(), 'status' => 500], 500);
            }
            }
        }

        if (isset($validator['detail_portion'])) {
            $validator['detail_portion'] = Str::limit($validator['detail_portion'], 250, '...');
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
                        unlink(public_path($img_path));
                    }

                    if (Str::length($thumb_path) > 0 && file_exists(public_path($thumb_path))) {
                        unlink(public_path($thumb_path));
                    }

                    $manager->read($img)->scale(width: 300)->save(public_path('images/pages_thumbnail/' . $imagename_thumb));
                    $img->storeAs('images/pages', $imagename, 'public');

                    $img_path = 'images/pages/' . $imagename;
                    $thumb_path = 'images/pages_thumbnail/' . $imagename_thumb;
                } catch (Exception $e) {
                    return response()->json(['message' => 'Error processing file: ' . $e->getMessage(), 'status' => 500], 500);
                }
            } else {
                return response()->json(['message' => ['img' => 'عفوا، حجم الصورة يتجاوز الحد المسموح به أو الملف غير صالح'], 'status' => 409], 200);
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
                return response()->json(['message' => 'Error processing file: ' . $e->getMessage(), 'status' => 500], 500);
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
        $record->detail_portion = Str::limit($request->detail_portion, 250, '...');
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
        if ($page->slug !== $slug) {
            return response()->json(['message' => 'الصفحة غير موجودة أو تم حذفها مسبقاً', 'status' => 404], 404);
        }

        if (Str::length($page->img) > 0 && file_exists(public_path($page->img))) {
            unlink(public_path($page->img));
        }

        if (Str::length($page->thumb_img) > 0 && file_exists(public_path($page->thumb_img))) {
            unlink(public_path($page->thumb_img));
        }

        if (Str::length($page->file) > 0 && file_exists(public_path($page->file))) {
            unlink(public_path($page->file));
        }

        $page->delete();
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
