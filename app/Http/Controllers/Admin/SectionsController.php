<?php

namespace App\Http\Controllers\Admin;

use App\Models\Section;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SectionRequest;

class SectionsController extends Controller
{
    public function index(Request $request)
    {
        $itemsPerPage = (int) htmlspecialchars($request->get('items') ?? 15);
        if ($itemsPerPage <= 0) {
            $itemsPerPage = 1000;
        }
        $search = htmlspecialchars($request->get('search') ?? '');
        $search = trim(preg_replace('/[+\-><\(\)~*\"@]+/', ' ', $search));

        $query = Section::select('sections.id', 'sections.department_id', 'sections.name', 'sections.name_en', 'sections.active')
            ->with('department:id,name');

        $query->whereHas('department', function ($q) {
            $q->where('active', '1');
        });

        $authUser = Auth::user();
        if ($authUser && $authUser->role != 1 && $authUser->is_college_rep) {
            $collegeId = $authUser->staff_latest_by_id?->department?->department_id;
            if ($collegeId) {
                $query->where('sections.department_id', $collegeId);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereRaw('MATCH(sections.name, sections.name_en) AGAINST(? IN BOOLEAN MODE)', [implode(' ', array_map(function($w) { $w = trim(preg_replace('/[+\-\><\(\)~*"@]+/', '', $w)); if (!$w) return ''; $prefixes = ['', 'ال', 'وال', 'بال', 'فال', 'لل', 'كال']; $group = []; foreach($prefixes as $p) { $group[] = $p . $w . '*'; } return '+(' . implode(' ', $group) . ')'; }, explode(' ', $search)))])
                  ->orWhereHas('department', function ($dq) use ($search) {
                      $dq->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        if ($request->has('orderby') && $request->has('ascend')) {
            $orderBy = $request->get('orderby');
            $sortDirection = ($request->get('ascend') === 'true' || $request->get('ascend') === 'asc' || $request->get('ascend') == '1') ? 'asc' : 'desc';

            if ($orderBy === 'department.name') {
                $query->join('departments', 'departments.id', '=', 'sections.department_id')
                      ->orderBy('departments.name', $sortDirection);
            } else {
                $query->orderBy('sections.' . ltrim($orderBy, 'sections.'), $sortDirection);
            }
        } else {
            $query->orderBy('sections.id', 'desc');
        }

        return response()->json(['result' => $query->paginate($itemsPerPage)], 200);
    }

    public function show($id)
    {
        $data = Section::with('department:id,college_id,school_id')->select('*')->where('id', $id)->first();
        
        if ($data) {
            $data->makeVisible(['department_id']);
        }

        return response()->json(['result' => $data, 'status' => 200]);
    }

    public function store(SectionRequest $request)
    {
        $validator = $request->validated();

        $active = 0;
        if (isset($request->active) && (int) $request->active == 1) {
            $active = 1;
        }
        Section::create(array_merge(
            $validator,
            ['user_id' => Auth::user()->id],
            ['active' => $active],
        ));
        return response()->json(['message' => 'created', 'status' => 201]);
    }

    public function update(SectionRequest $request, string $id)
    {
        $record = Section::findOrFail($id);
        $active = 0;
        if (isset($request->active) && $request->active == 1) {
            $active = 1;
        }

        $record->department_id = $request->department_id;

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
        $data = Section::findOrFail($id);
        $data->delete();
        return response()->json(['message' => 'deleted', 'status' => 200]);
    }

    public function list(Request $request)
    {
        $data = Section::select('id', 'name')->where('department_id', $request->department_id)->where('active', 1)->orderBy('name', 'asc')->get();
        return response()->json(['departments' => $data], 200);
    }

    public function print()
    {
        $sections = Section::with('department')->orderBy('department_id')->get();
        return view('admin.sections_print', compact('sections'));
    }
}
