<?php

namespace App\Http\Controllers\Admin;

// use App\Models\Category;
// use App\Models\CategoryPage;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;
// use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Validator;

use App\Models\CategoryPage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CategoryPageRequest;

class CategoryPageController extends Controller
{
    public function index(Request $request)
    {
        $itemsPerPage = (int) htmlspecialchars($request->get('items') ?? 15);
        if ($itemsPerPage <= 0) {
            $itemsPerPage = 1000;
        }
        $search = htmlspecialchars($request->get('search') ?? '');
        $search = trim(preg_replace('/[+\-><\(\)~*\"@]+/', ' ', $search));

        $query = CategoryPage::select('category_pages.id', 'category_pages.category_id', 'category_pages.title', 'category_pages.active')
            ->with('category:id,title');

        $query->whereHas('category', function ($q) {
            $q->where('active', '1');
        });

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereRaw('MATCH(category_pages.title) AGAINST(? IN BOOLEAN MODE)', [implode(' ', array_map(function($w) { $w = trim(preg_replace('/[+\-\><\(\)~*"@]+/', '', $w)); if (!$w) return ''; $prefixes = ['', 'ال', 'وال', 'بال', 'فال', 'لل', 'كال']; $group = []; foreach($prefixes as $p) { $group[] = $p . $w . '*'; } return '+(' . implode(' ', $group) . ')'; }, explode(' ', $search)))])
                  ->orWhereHas('category', function ($cq) use ($search) {
                      $cq->where('title', 'like', '%' . $search . '%');
                  });
            });
        }

        if ($request->has('orderby') && $request->has('ascend')) {
            $orderBy = $request->get('orderby');
            $sortDirection = ($request->get('ascend') === 'true' || $request->get('ascend') === 'asc' || $request->get('ascend') == '1') ? 'asc' : 'desc';

            if ($orderBy === 'category.title') {
                $query->join('categories', 'categories.id', '=', 'category_pages.category_id')
                      ->orderBy('categories.title', $sortDirection);
            } else {
                $query->orderBy('category_pages.' . ltrim($orderBy, 'category_pages.'), $sortDirection);
            }
        } else {
            $query->orderBy('category_pages.id', 'desc');
        }

        return response()->json(['result' => $query->paginate($itemsPerPage)], 200);
    }

    public function show($id)
    {
        $data = CategoryPage::select('category_id','title','url','active')->where('id',$id)->first();

        return response()->json(['result' => $data, 'status' => 200]);
    }


    public function store(CategoryPageRequest $request)
    {
        $validator = $request->validated();

        $active = 0;
        if (isset($request->active) && (int)$request->active == 1) {
            $active = 1;
        }

        CategoryPage::create(array_merge(
            $validator,
            ['user_id' => Auth::user()->id],
            ['active' => $active],
        ));
        return response()->json(['message' => 'created', 'status' => 201]);
    }

    public function update(CategoryPageRequest $request, string $id)
    {
        $record = CategoryPage::findOrFail($id);
        $active = 0;
        if (isset($request->active) && $request->active == 1) {
            $active = 1;
        }

        $record->category_id = $request->category_id;
        $record->title = $request->title;
        $record->url = $request->url;
        $record->active = $active;
        $record->user_id = Auth::user()->id;

        if ($record->isDirty()) { // if record data changed
            $record->save();
            return response()->json(['message' => 'updated', 'status' => 204]);
        }
        return response()->json(['message' => 'not changed', 'status' => 304]);
    }

    public function destroy(CategoryPage $categoryPage)
    {
		$categoryPage::find($categoryPage->id)->delete();
        return response()->json(['message' => 'deleted', 'status' => 200]);
    }

    public function list(Request $request)
    {
        if(Auth::user()->role != 1){
            return response()->json(['message' => 'Not Found.'], 404);
        }

            // $category_pages = CategoryPage::where([['category_id', '=', DB::raw('"' . $request->id . '"')]])
            //                   ->whereNull('deleted_at')->get();



                    $res = CategoryPage::select('id','title')->whereHas('category' , function ($query) {
                        $query->where('active', 1);
                    })->where('active', 1)->where('category_id', $request->id);

                    // $res = CategoryPage::with(['categoryPages' => function ($query) use($request) {
                    //     $query->select('id', 'title');
                    //     $query->where('category_id', $request->id);
                    // }])
                           //->whereHas('categoryPages')->whereHas('users.staff.department.college');

                    return response()->json(['pages' => $res->get()], 200);
    }

}
