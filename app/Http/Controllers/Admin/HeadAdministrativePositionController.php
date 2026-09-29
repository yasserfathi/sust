<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\HeadAdministrativePosition;
use App\Http\Requests\HeadAdministrativePositionRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HeadAdministrativePositionController extends Controller
{
	public function index(Request $request)
	{
		$itemsPerPage = max((int) ($request->get('items') ?? 15), 1); // Ensure >= 1
		$search = htmlspecialchars($request->get('search') ?? '');
		$query = HeadAdministrativePosition::select('id', 'administrative_positions_id', 'college_id', 'user_id', 'start_date', 'end_date')
			->with([
				'user:id,name',
				'college:id,name',
				'administrativePosition:id,title,title_en',
			]);

		if (!empty($search)) {
			$query->where(function ($q) use ($search) {
				$q->whereHas('administrativePosition', function ($adminPosQuery) use ($search) {
						$adminPosQuery->where('title', 'like', '%' . $search . '%')
							->orWhere('title_en', 'like', '%' . $search . '%');
					})
					->orWhereHas('college', function ($collegeQuery) use ($search) {
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


	public function store(HeadAdministrativePositionRequest $request)
	{
		$validator = $request->validated();

		// Get the latest assignment for this specific rank and college
		$latestRank = HeadAdministrativePosition::select('id', 'start_date')
			->where('administrative_positions_id', $request->administrative_positions_id)
			->where('college_id', $request->college_id)
			->latest('start_date')
			->first();

		if ($latestRank) {
			//check request start date if after last start date
			if (Carbon::parse($request->start_date)->lt(Carbon::parse($latestRank->start_date))) {
				return response()->json(['message' => 'The new start date is earlier than the previous one', 'status' => 409]);
			}
			$end_date = Carbon::parse($request->start_date)->subDays(1)->toDateString();

			//update last Rank End Date
			$latestRank->update(['end_date' => $end_date]);
		}

		HeadAdministrativePosition::create(array_merge(
			$validator,
			['auth_id' => Auth::user()->id],
		));
		return response()->json(['message' => 'created', 'status' => 201]);
	}

	public function show($id)
	{
		$data = HeadAdministrativePosition::with([
			'user:id,name',
			'user.staff_latest:staff_employs.id,staff_employs.user_id,staff_employs.department_id,staff_employs.hire_date',
			'user.staff_latest.department:id,name',
			'college:id,name',
			'administrativePosition:id,title,title_en',
		])
			->select('id', 'administrative_positions_id', 'college_id', 'user_id', 'start_date', 'end_date')->find($id);

		if (!$data) {
			return response()->json(['message' => 'Not found'], 404);
		}

		return response()->json(['result' => $data, 'status' => 200]);
	}

	public function update(HeadAdministrativePositionRequest $request, $id)
	{
		$record = HeadAdministrativePosition::find($id);
		if (!$record) {
			return response()->json(['message' => 'Not found'], 404);
		}

		$record->administrative_positions_id = $request->administrative_positions_id;
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
		$data = HeadAdministrativePosition::find($id);
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
			$data = HeadAdministrativePosition::with(['user', 'college', 'administrativePosition'])
				->where('college_id', $request->id)
				->orderBy('start_date', 'desc')
				->first();

			if ($data != null) {
				$collegeName = $data->college ? $data->college->name : '';
				$userName = $data->user ? $data->user->name : '';
				$rankNameAr = $data->administrativePosition ? $data->administrativePosition->title : 'المنصب';
				$startDate = Carbon::parse($data->start_date)->format('Y-m-d');

				$str = '<div class="col-md-12"><hr style="height:0.5px" /><p class="p-3 col-md-12 current">' . $rankNameAr . ' الحالي لكلية <label>' . $collegeName . '</label> : <label> ' . $userName . '</label> من يوم  <label id="from">' . $startDate . '</label></p><hr /></div>';
			}
		}
		return $str;
	}
}
