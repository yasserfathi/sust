<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Support\Arr;
use App\Models\CategoryPage;
use Illuminate\Http\Request;
use App\Models\CategoryPageUser;
use App\Http\Requests\CategoryPageUserRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CategoryPageUserController extends Controller
{
    public function index(Request $request, $user_id)
    {
        $itemsPerPage = htmlspecialchars($request->get('items') ?? 15);

        $categories_pages = Category::with('categoryPage:id,category_id,title')
            ->whereHas('categoryPage.categoryPageUsers.users', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })->where('active', 1)->select('id', 'title')->get();

        $category_page_for_user = CategoryPageUser::with('categoryPages:id')->whereHas('categoryPages', function ($query) {
            $query->where('active', 1);
        })->whereHas('users.staff.department.college')
            ->where('user_id', $user_id)->get();

        $faltten_array = Arr::flatten($category_page_for_user->toArray());

        foreach ($categories_pages as $key => $val) {
            foreach ($val->categoryPage as $key2 => $val2) {
                if (in_array($val2->id, $faltten_array) == false) {
                    unset($categories_pages[$key]->categoryPage[$key2]);
                }
            }
        }

        return response()->json(['result' => $categories_pages->paginate((int)$itemsPerPage)], 200);
    }

    public function show($user_id, $category_id)
    {
        $category_pages = CategoryPage::select('id', 'title')->whereHas('category', function ($query) {
            $query->where('active', 1);
        })->where('active', 1)->where('category_id', $category_id)->get();

        $category_page_for_user = CategoryPageUser::with('categoryPages:id')->whereHas('categoryPages', function ($query) use ($category_id) {
            $query->where('category_id', $category_id)->where('active', 1);
        })->whereHas('users.staff.department.college')
            ->where('user_id', $user_id)->get();


        $faltten_array = Arr::flatten($category_page_for_user->toArray());

        $category_pages->each(function ($collection) use ($faltten_array) {
            $collection->checked = in_array($collection->id, $faltten_array);
        });

        return response()->json(['pages' => $category_pages], 200);
    }

    public function store(CategoryPageUserRequest $request)
    {
        $pages = explode(',', $request->pages);
        
        $existingRecords = CategoryPageUser::withTrashed()
            ->whereIn('category_page_id', $pages)
            ->where('user_id', $request->user_id)
            ->get()
            ->keyBy('category_page_id');

        $inserts = [];
        $authId = Auth::user()->id;
        $now = \Carbon\Carbon::now();

        foreach ($pages as $pageId) {
            $record = $existingRecords->get($pageId);

            if ($record) {
                if ($record->trashed()) {
                    $record->restore();
                }
            } else {
                $inserts[] = [
                    'category_page_id' => $pageId,
                    'user_id' => $request->user_id,
                    'auth_id' => $authId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        if (!empty($inserts)) {
            CategoryPageUser::insert($inserts);
        }

        return response()->json(['message' => 'created', 'status' => 201]);
    }


    public function update(CategoryPageUserRequest $request)
    {
        $pages = explode(',', $request->pages);
        $pageIdsToProcess = [];
        $pageIdsToDelete = [];

        foreach ($pages as $page) {
            if ($page > 0) {
                $pageIdsToProcess[] = $page;
            } else {
                $pageIdsToDelete[] = $page * -1;
            }
        }

        if (!empty($pageIdsToDelete)) {
            CategoryPageUser::where('user_id', $request->user_id)
                ->whereIn('category_page_id', $pageIdsToDelete)
                ->delete();
        }

        if (!empty($pageIdsToProcess)) {
            $existingRecords = CategoryPageUser::withTrashed()
                ->whereIn('category_page_id', $pageIdsToProcess)
                ->where('user_id', $request->user_id)
                ->get()
                ->keyBy('category_page_id');

            $inserts = [];
            $authId = Auth::user()->id;
            $now = \Carbon\Carbon::now();

            foreach ($pageIdsToProcess as $pageId) {
                $record = $existingRecords->get($pageId);

                if ($record) {
                    if ($record->trashed()) {
                        $record->restore();
                    }
                } else {
                    $inserts[] = [
                        'category_page_id' => $pageId,
                        'user_id' => $request->user_id,
                        'auth_id' => $authId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            if (!empty($inserts)) {
                CategoryPageUser::insert($inserts);
            }
        }

        return response()->json(['message' => 'updated', 'status' => 204]);
    }

    public function destroy($user_id, $category_id)
    {
        CategoryPageUser::with('categoryPages:id')->whereHas('categoryPages', function ($query) use ($category_id) {
            $query->where('category_id', $category_id)->where('active', 1);
        })->whereHas('users.staff.department.college')
            ->where('user_id', $user_id)->delete();
        return response()->json(['message' => 'deleted', 'status' => 200]);
    }
}
