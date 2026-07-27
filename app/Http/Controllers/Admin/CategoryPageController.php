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
        $itemsPerPage = htmlspecialchars($request->get('items') ?? 15);
        $search = htmlspecialchars($request->get('search') ?? '');

        //Department collection
        $result_departments = CategoryPage::select('id', 'category_id', 'title', 'active');

        if (!empty($search)) {
            $result_departments->where('title', 'like', '%' . $search . '%');
        }

        $result_departments->with('category:id,title')->whereHas('category', function ($query) {
            $query->where('active', '1');
        });


        // ===================================

        //Department collection with category Name Search
        $result_college_name = CategoryPage::select('id', 'category_id', 'title', 'active');

        $result_college_name->with('category:id,title')->whereHas('category', function ($query) use ($search) {
            if (!empty($search)) {
                $query->where('title', 'like', '%' . $search . '%');
            }
        });

        $all = $result_departments->get()->merge($result_college_name->get());

        if ($request->exists('orderby') && $request->exists('ascend')) {
            $all = $all->sortBy([[$request->get('orderby'), $request->get('ascend')]]);
        }

        return response()->json(['result' => $all->paginate((int)$itemsPerPage)], 200);
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
