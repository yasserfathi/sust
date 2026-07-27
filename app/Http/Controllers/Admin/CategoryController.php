<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{

    public function index(Request $request)
    {
        $itemsPerPage = htmlspecialchars($request->get('items') ?? 15);
        if ($itemsPerPage < 0) {
            $itemsPerPage = 0;
        }
        $search = htmlspecialchars($request->get('search') ?? '');

        $data = Category::select('id', 'title', 'active');

        if (!empty($search)) {
            $data->where('title', 'like', '%' . $search . '%');
        }

        if (!$request->exists('orderby') && !empty($request->get('ascend'))) {
            $data->orderBy($request->get('orderby'), $request->get('ascend'));
        } else {
            $data->orderBy('id', 'desc');
        }

        $resource = $data->paginate((int)$itemsPerPage);

        return response()->json([
            'result' => $resource->setCollection($resource->getCollection()->makeVisible('id'))
        ], 200);
    }

    public function show($id)
    {
        $data = Category::select('title', 'active')->where('id',$id)->first();

        return response()->json(['result' => $data, 'status' => 200]);
    }


    public function store(CategoryRequest $request)
    {
        $validator = $request->validated();
        $active = 0;
        if (isset($request->active) && (int)$request->active == 1) {
            $active = 1;
        }
        Category::create(array_merge(
            $validator,
            ['user_id' => Auth::user()->id],
            ['active' => $active],
        ));
        return response()->json(['message' => 'created', 'status' => 201]);
    }

    public function update(CategoryRequest $request,string $id)
    {
        $record = Category::findOrFail($id);

        $active = 0;
		if(isset($request->active) && $request->active ==1) {
		    $active = 1;
	    }

        $record->title = $request->title;
        $record->active = $active;
        $record->user_id = Auth::user()->id;

        if ($record->isDirty()) { // if record data changed
            $record->save();
            return response()->json(['message' => 'updated', 'status' => 204]);
        } else {
            return response()->json(['message' => 'not changed', 'status' => 304]);
        }
    }

    public function destroy(Category $category)
    {
        $category::find($category->id)->delete();
        return response()->json(['message' => 'deleted', 'status' => 200]);
    }

    public function list()
    {
        $data = Category::select('id', 'title')
            ->where('active', '1')
            ->get();
        return response()->json([
            'categories' => $data
        ], 200);
    }
}
