<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreCollegeStrategicRequest;
use App\Models\CollegeStrategic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CollegeStrategicController extends Controller
{
    public function index(Request $request)
    {
        // Validate and sanitize items per page
        $itemsPerPage = (int)htmlspecialchars($request->get('items', 15));
        $itemsPerPage = max(0, $itemsPerPage); // Ensure it's not negative
        
        // Sanitize search input
        $search = htmlspecialchars($request->get('search', ''));
        
        // Base query
        $query = CollegeStrategic::select('id', 'college_id', 
                    DB::raw('(CASE WHEN lang = 1 THEN "اللغة العربية" ELSE "اللغة الانجليزية" END) AS lang')
                )
                ->with('college:id,name')
                ->whereHas('college', function ($q) use ($search) {
                    $q->where('user_id', 1);
                    if (!empty($search)) {
                        $q->where('name', 'like', '%' . $search . '%');
                    }
                });
        
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

    public function show($id)
    {
        $edit_data = CollegeStrategic::select('college_id', 'vision', 'mission', 'goals', 'keywords', 'lang')
            ->where('id', $id)
            ->first();

        return response()->json(['result' => $edit_data, 'status' => 200]);
    }

    public function store(StoreCollegeStrategicRequest $request)
    {
        $validator = $request->validated();

        CollegeStrategic::create(array_merge(
            $validator,
            ['auth_id' => Auth::user()->id]
        ));
        return response()->json(['message' => 'created', 'status' => 201]);
    }

    public function update(StoreCollegeStrategicRequest $request, string $id)
    {
        $record = CollegeStrategic::findOrFail($id);
        $record->college_id = $request->college_id;
        $record->lang = $request->lang;
        $record->vision = $request->vision;
        $record->mission = $request->mission;
        $record->goals = $request->goals;
        $record->keywords = $request->keywords;
        $record->auth_id = Auth::user()->id;

        if ($record->isDirty()) { // if record data changed
            $record->save();
            return response()->json(['message' => 'updated', 'status' => 204]);
        } else {
            return response()->json(['message' => 'not changed', 'status' => 304]);
        }
    }

    public function destroy(string $id)
    {
        CollegeStrategic::find($id)->delete();
        return response()->json(['message' => 'deleted', 'status' => 200]);
    }
}
