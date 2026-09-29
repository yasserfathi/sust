<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Calendar;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CalendarRequest;
use Illuminate\Support\Str;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $itemsPerPage = (int) htmlspecialchars($request->get('items') ?? 15);
        if ($itemsPerPage <= 0) {
            $itemsPerPage = 1000; // Fallback to a large number instead of fetching all into memory if 0
        }

        $search = htmlspecialchars($request->get('search') ?? '');

        $query = Calendar::select('calendars.id', 'calendars.college_id', 'calendars.year', 'calendars.file')
            ->whereHas('college')
            ->with('college:id,name')
            ->where('calendars.lang', 1);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('calendars.year', 'like', '%' . $search . '%')
                  ->orWhereHas('college', function ($cq) use ($search) {
                      $cq->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        $authUser = Auth::user();
        if ($authUser->role == 3 || $authUser->role == 2) {
            if ($authUser->is_college_rep) {
                $collegeId = $authUser->staff_latest_by_id?->department?->college_id;
                $query->where('calendars.college_id', $collegeId);
            } else if ($authUser->role == 3) {
                $query->where('calendars.user_id', $authUser->id);
            } else {
                // For role 2 who is not a rep, filter by their colleges
                $query->whereHas('college', function ($q) use ($authUser) {
                    $q->where('user_id', $authUser->id);
                });
            }
        }

        if ($request->has('orderby') && $request->has('ascend')) {
            $orderBy = $request->get('orderby');
            $sortDirection = ($request->get('ascend') === 'true' || $request->get('ascend') === 'asc' || $request->get('ascend') == '1') ? 'asc' : 'desc';

            if ($orderBy === 'college.name') {
                $query->join('colleges', 'colleges.id', '=', 'calendars.college_id')
                      ->orderBy('colleges.name', $sortDirection);
            } else {
                $query->orderBy('calendars.' . ltrim($orderBy, 'calendars.'), $sortDirection);
            }
        }

        return response()->json(['result' => $query->paginate($itemsPerPage)], 200);
    }

    public function show(Calendar $calendar)
    {
        $data = Calendar::select('id', 'college_id', 'lang', 'year', 'file')
            ->with([
                'college' => function ($query) {
                    $query->select('id', 'name');
                }
            ])
            ->whereHas('college')
            ->where('id', $calendar->id)->first();
        return response()->json(['result' => $data, 'status' => 200]);
    }


    public function store(CalendarRequest $request)
    {
        $validator = $request->validated();

        $file_path = '';
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            try {
                $file = $request->file('file');
                $rand = hexdec(uniqid());
                $imagename = $rand . '.' . $file->getClientOriginalExtension();
                $file_path = $file->storeAs('files', $imagename, 'public');
            } catch (Exception $e) {
                return response()->json(['message' => 'Error processing file: ' . $e->getMessage(), 'status' => 500], 500);
            }
        }

        Calendar::create(array_merge(
            $validator,
            ['auth_id' => Auth::user()->id],
            ['file' => $file_path],
            ['lang' => 1],
        ));
        return response()->json(['message' => 'created', 'status' => 201]);
    }

    public function update(CalendarRequest $request,Calendar $calendar)
    {
        $record = Calendar::find($calendar->id);
        $file_path = $record->file;
        if ($request->hasFile('file')) {
            if ($request->file('file')->isValid()) {
                try {
                    $file = $request->file('file');
                    $rand = hexdec(uniqid());
                    $imagename = $rand . '.' . $file->getClientOriginalExtension();
                    if(Str::length($file_path) > 0 && file_exists(public_path($file_path))) {
                        unlink(public_path($file_path));
                    }
                    $file_path = $file->storeAs('files', $imagename, 'public');
                } catch (Exception $e) {
                return response()->json(['message' => 'Error processing file: ' . $e->getMessage(), 'status' => 500], 500);
            }
            }
        }

        $record->year = $request->year;
        $record->file = $file_path;
        $record->auth_id = Auth::user()->id;

        if ($record->isDirty()) { // if record data changed
            $record->save();
            return response()->json(['message' => 'updated', 'status' => 204]);
        } else {
            return response()->json(['message' => 'not changed', 'status' => 304]);
        }
    }

    public function destroy(Calendar $calendar)
    {
        if(Str::length($calendar->file) > 0 && file_exists(public_path($calendar->file))) {
            unlink(public_path($calendar->file));
        }
        $calendar->delete();
        return response()->json(['message' => 'deleted', 'status' => 200]);
    }
}
