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
        $itemsPerPage = htmlspecialchars($request->get('items') ?? 15);

        if ($itemsPerPage < 0) {
            $itemsPerPage = 0;
        }

        $search = htmlspecialchars($request->get('search') ?? '');

        $select_fileds = ['id', 'college_id', 'year', 'file'];

        //collection serach Calendr year
        $result_calendar_year = Calendar::select($select_fileds)
            ->whereHas('college')->with('college:id,name')->where('lang', 1);

        if (!empty($search)) {
            $result_calendar_year->where('year', 'like', '%' . $search . '%');
        }

        if (Auth::user()->role == 3) {
            $result_calendar_year->where('user_id', Auth::user()->id);
        }

        // ===================================

        //collection serach college name

        $result_college_name = Calendar::select($select_fileds)
            ->whereHas(
                'college',
                function ($query) use ($search) {
                    if (!empty($search)) {
                        $query->where('name', 'like', '%' . $search . '%');
                    }
                },
            )->with('college:id,name')->where('lang', 1);

        if (Auth::user()->role == 3) {
            $result_college_name->where('user_id', Auth::user()->id);
        }

        $all = $result_calendar_year->get()->merge($result_college_name->get());

        if ($request->exists('orderby') && $request->exists('ascend')) {
            $all = $all->sortBy([[$request->get('orderby'), $request->get('ascend')]]);
        }

        if ($itemsPerPage == 0) {
            $itemsPerPage = count($all);
        }

        return response()->json(['result' => $all->paginate((int)$itemsPerPage)], 200);
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
            }
        }

        Calendar::create(array_merge(
            $validator,
            ['auth_id' => Auth::user()->id],
            ['file' => $file_path],
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
                        unlink($file_path);
                    }
                    $file_path = $file->storeAs('files', $imagename, 'public');
                } catch (Exception $e) {
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
        $data = Calendar::find($calendar->id);
        if(Str::length($data->file) > 0 && file_exists(public_path($data->file))) {
            unlink($data->file);
        }
        $data->delete();
        return response()->json(['message' => 'deleted', 'status' => 200]);
    }
}
