<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\HeadOfDepartment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\HeadOfDepartmentRequest;

class HeadOfDepartmentController extends Controller
{
	public function index(Request $request)
	{
		$itemsPerPage = max((int) ($request->get('items') ?? 15), 1); // Ensure >= 1
		$search = htmlspecialchars($request->get('search') ?? '');

		$query = HeadOfDepartment::with([
			'department' => function ($query) {
				$query->select('id', 'name', 'college_id');
			},
			'department.college' => function ($query) {
				$query->select('id', 'name');
			},
			'staff' => function ($query) {
				$query->select('id');
			},
			'staff.user' => function ($query) {
				$query->select('id', 'name');
			}
		])
			->select('id', 'department_id', 'user_id', 'start_date', 'end_date');

		if (!empty($search)) {
			$query->where(function ($q) use ($search) {
				$q->whereHas('department', function ($departmentQuery) use ($search) {
					$departmentQuery->where('name', 'like', '%' . $search . '%');
				})
					->orWhereHas('department.college', function ($collegeQuery) use ($search) {
						$collegeQuery->where('name', 'like', '%' . $search . '%');
					})
					->orWhereHas('staff.user', function ($staffQuery) use ($search) {
						$staffQuery->where('name', 'like', '%' . $search . '%');
					});
			});
		}

		if ($request->has('orderby') && $request->has('ascend')) {
			$query->orderBy($request->get('orderby'), $request->get('ascend') === 'true' ? 'asc' : 'desc');
		}

		return response()->json([
			'result' => $query->paginate($itemsPerPage),
		], 200);
	}


	public function store(HeadOfDepartmentRequest $request)
	{
		$validator = $request->validated();
		$latestHead = HeadOfDepartment::select('id', 'start_date')->where('user_id', $request->user_id)
			->latest()
			->first();

		if ($latestHead->count() > 0) {
			//check request start date if after last start date
			if (Carbon::parse($request->start_date)->lt(Carbon::parse($latestHead->start_date))) {
				return response()->json(['message' => 'The new start date is earlier than the previous one', 'status' => 409]);

			}
			$end_date = Carbon::parse($request->start_date)->subDays(1)->toDateString(); // get last date for latest Head of 

			//update last Head of Department End Date
			$head = HeadOfDepartment::find($latestHead->id);
			$head->end_date = $end_date;
			$head->save();
		}

		HeadOfDepartment::create(array_merge(
			$validator,
			['auth_id' => Auth::user()->id],
		));
		return response()->json(['message' => 'created', 'status' => 201]);
	}

	public function show($id)
	{
		$data = HeadOfDepartment::with([
			'department' => function ($query) {
				$query->select('id', 'name', 'college_id');
			},
			'department.college' => function ($query) {
				$query->select('id', 'name');
			},
			'staff' => function ($query) {
				$query->select('id');
			},
			'staff.user' => function ($query) {
				$query->select('id', 'name');
			}
		])
			->select('id', 'department_id', 'user_id', 'start_date', 'end_date')->where('id', $id)->first();

		return response()->json(['result' => $data, 'status' => 200]);
	}

	public function update(Request $request, string $id)
	{
		$record = HeadOfDepartment::find($id);
		$record->department_id = $request->department_id;
		$record->user_id = $request->user_id;
		$record->start_date = $request->start_date;
		$record->end_date = $request->end_date;
		$record->auth_id = Auth::user()->id;
		if ($record->isDirty()) { // if record data changed
			$record->save();
			return response()->json(['message' => 'updated', 'status' => 204]);
		}
		return response()->json(['message' => 'not changed', 'status' => 304]);
	}

	public function destroy(HeadOfDepartment $headOfDepartment)
	{
		$headOfDepartment::find($headOfDepartment->id)->delete();
		return redirect()->route('head_of_department.index');
	}
	public function head(Request $request)
	{
		$str = '';
		if ($request->id != '') {
			$data = HeadOfDepartment::with('user')
				->select('user_id', 'start_date')->where([['department_id', '=', DB::raw('"' . $request->id . '"')]])->orderBy('start_date', 'desc')->first();
			if ($data != null)
				$str = '<div class="col-md-12"><hr style="height:0.5px" /><p class="p-3 col-md-12 current">رئيس القسم الحالي لقسم <label>' . $data->user->staff->department->name . '</label> : <label> ' . $data->user->name . '</label> من يوم  <label id="from">' . Carbon::parse($data->start_date)->format('Y-m-d') . '</label></p><hr /></div>';
		}
		return $str;
	}
}
