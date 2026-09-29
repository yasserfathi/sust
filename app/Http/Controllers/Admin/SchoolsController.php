<?php

namespace App\Http\Controllers\Admin;

use App\Models\School;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SchoolRequest;

class SchoolsController extends Controller
{
    public function index(Request $request)
    {
        $itemsPerPage = (int) htmlspecialchars($request->get('items') ?? 15);
        if ($itemsPerPage <= 0) {
            $itemsPerPage = 1000;
        }
        $search = htmlspecialchars($request->get('search') ?? '');
        $search = trim(preg_replace('/[+\-><\(\)~*\"@]+/', ' ', $search));

        $query = School::select('schools.id', 'schools.college_id', 'schools.name', 'schools.name_en', 'schools.active')
            ->with('college:id,name');

        $query->whereHas('college', function ($q) {
            $q->where('active', '1');
        });

        $authUser = Auth::user();
        if ($authUser && $authUser->role != 1 && $authUser->is_college_rep) {
            $collegeId = $authUser->staff_latest_by_id?->department?->college_id;
            if ($collegeId) {
                $query->where('schools.college_id', $collegeId);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereRaw('MATCH(schools.name, schools.name_en) AGAINST(? IN BOOLEAN MODE)', [implode(' ', array_map(function($w) { $w = trim(preg_replace('/[+\-\><\(\)~*"@]+/', '', $w)); if (!$w) return ''; $prefixes = ['', 'ال', 'وال', 'بال', 'فال', 'لل', 'كال']; $group = []; foreach($prefixes as $p) { $group[] = $p . $w . '*'; } return '+(' . implode(' ', $group) . ')'; }, explode(' ', $search)))])
                  ->orWhereHas('college', function ($cq) use ($search) {
                      $cq->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        if ($request->has('orderby') && $request->has('ascend')) {
            $orderBy = $request->get('orderby');
            $sortDirection = ($request->get('ascend') === 'true' || $request->get('ascend') === 'asc' || $request->get('ascend') == '1') ? 'asc' : 'desc';

            if ($orderBy === 'college.name') {
                $query->join('colleges', 'colleges.id', '=', 'schools.college_id')
                      ->orderBy('colleges.name', $sortDirection);
            } else {
                $query->orderBy('schools.' . ltrim($orderBy, 'schools.'), $sortDirection);
            }
        } else {
            $query->orderBy('schools.id', 'desc');
        }

        return response()->json(['result' => $query->paginate($itemsPerPage)], 200);
    }

    public function show($id)
    {
        $data = School::select('*')->where('id', $id)->first();
        
        if ($data) {
            $data->makeVisible(['college_id']);
        }

        return response()->json(['result' => $data, 'status' => 200]);
    }

    public function store(SchoolRequest $request)
    {
        $validator = $request->validated();

        $active = 0;
        if (isset($request->active) && (int) $request->active == 1) {
            $active = 1;
        }
        School::create(array_merge(
            $validator,
            ['user_id' => Auth::user()->id],
            ['active' => $active],
        ));
        return response()->json(['message' => 'created', 'status' => 201]);
    }

    public function update(SchoolRequest $request, string $id)
    {
        $record = School::findOrFail($id);
        $active = 0;
        if (isset($request->active) && $request->active == 1) {
            $active = 1;
        }

        $record->college_id = $request->college_id;
        $record->name = $request->name;
        $record->name_en = $request->name_en;
        $record->keywords = $request->keywords;
        $record->description = $request->description;
        $record->keywords_ar = $request->keywords_ar;
        $record->description_ar = $request->description_ar;
        $record->active = $active;
        $record->user_id = Auth::user()->id;

        if ($record->isDirty()) { // if record data changed
            $record->save();
            return response()->json(['message' => 'updated', 'status' => 204]);
        }
        return response()->json(['message' => 'not changed', 'status' => 304]);
    }

    public function destroy(string $id)
    {
        $data = School::findOrFail($id);
        $data->delete();
        return response()->json(['message' => 'deleted', 'status' => 200]);
    }

    public function list(Request $request)
    {
        $data = School::select('id', 'name')->where('college_id', $request->college_id)->where('active', 1)->orderBy('name', 'asc')->get();
        return response()->json(['schools' => $data], 200);
    }

    public function print()
    {
        $schools = School::with('college')->orderBy('college_id')->get();
        return view('admin.schools_print', compact('schools'));
    }
}
