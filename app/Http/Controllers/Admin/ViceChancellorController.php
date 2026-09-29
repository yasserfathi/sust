<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\ViceChancellor;
use App\Http\Requests\ViceChancellorRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ViceChancellorController extends Controller
{
	public function index(Request $request)
	{
		$itemsPerPage = max((int) ($request->get('items') ?? 15), 1); // Ensure >= 1
		$search = htmlspecialchars($request->get('search') ?? '');
		$query = ViceChancellor::select('id', 'college_id', 'user_id', 'start_date', 'end_date')
			->with([
				'user:id,name',
				// 'user.staff_latest:staff_employs.id,staff_employs.user_id,staff_employs.department_id',
				// 'user.staff_latest.department:id,name',
				'college:id,name',
			]);

		if (!empty($search)) {
			$query->where(function ($q) use ($search) {
				$q->whereHas('college', function ($collegeQuery) use ($search) {
					$collegeQuery->where('name', 'like', '%' . $search . '%');
				})
					->orWhereHas('user', function ($userQuery) use ($search) {
						$userQuery->where('name', 'like', '%' . $search . '%');
					});
			});
		}

		if ($request->has('orderby') && $request->has('ascend')) {
			$query->orderBy($request->get('orderby'), in_array(strtolower(trim($request->get('ascend') ?? '')), ['asc', 'true', '1']) ? 'asc' : 'desc');
		} else {
			$query->orderBy('start_date', 'desc');
		}

		return response()->json([
			'result' => $query->paginate($itemsPerPage),
		], 200);
	}


	public function store(ViceChancellorRequest $request)
	{
		$validator = $request->validated();
		$latestDean = ViceChancellor::select('id', 'start_date')
			->where('college_id', $request->college_id)
			->latest('start_date')
			->first();

		if ($latestDean) {
			//check request start date if after last start date
			if (Carbon::parse($request->start_date)->lt(Carbon::parse($latestDean->start_date))) {
				return response()->json(['message' => 'The new start date is earlier than the previous one', 'status' => 409]);

			}
			$end_date = Carbon::parse($request->start_date)->subDays(1)->toDateString(); // get last date for latest Head of 

			//update last Head of Department End Date
			$dean = ViceChancellor::find($latestDean->id);
			$dean->end_date = $end_date;
			$dean->save();
		}

		ViceChancellor::create(array_merge(
			$validator,
			['auth_id' => Auth::user()->id],
		));
		return response()->json(['message' => 'created', 'status' => 201]);
	}

	public function show($id)
	{
		$data = ViceChancellor::with([
			'user:id,name',
			'user.staff_latest:staff_employs.id,staff_employs.user_id,staff_employs.department_id',
			'user.staff_latest.department:id,name',
			'college:id,name',
		])
			->select('id', 'college_id', 'user_id', 'start_date', 'end_date')->find($id);

		if (!$data) {
			return response()->json(['message' => 'Not found'], 404);
		}

		return response()->json(['result' => $data, 'status' => 200]);
	}

	public function update(ViceChancellorRequest $request, $id)
	{
		$record = ViceChancellor::find($id);
		if (!$record) {
			return response()->json(['message' => 'Not found'], 404);
		}

		$record->college_id = $request->college_id;
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

	public function destroy($id)
	{
		$data = ViceChancellor::find($id);
		if (!$data) {
			return response()->json(['message' => 'Not found'], 404);
		}
		$data->delete();
		return response()->json(['message' => 'deleted', 'status' => 200]);
	}

	public function dean(Request $request)
	{
		$str = '';
		if ($request->id != '') {
			$data = ViceChancellor::with(['user', 'college'])
				->where('college_id', $request->id)
				->orderBy('start_date', 'desc')
				->first();

			if ($data != null) {
				$collegeName = $data->college ? $data->college->name : '';
				$userName = $data->user ? $data->user->name : '';
				$startDate = Carbon::parse($data->start_date)->format('Y-m-d');

				$str = '<div class="col-md-12"><hr style="height:0.5px" /><p class="p-3 col-md-12 current">عميد الكلية الحالي لكلية <label>' . $collegeName . '</label> : <label> ' . $userName . '</label> من يوم  <label id="from">' . $startDate . '</label></p><hr /></div>';
			}
		}
		return $str;
	}
}
