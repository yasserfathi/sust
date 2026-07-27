<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentData;


class StudentDataController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'university_number' => 'required|string'
        ]);

        $student = StudentData::select('full_name', 'college', 'department', 'semester', 'academic_year')
            ->where('university_number', $request->university_number)
            ->first();

        if (!$student) {
            return response()->json(['message' => 'الرقم الجامعي غير صحيح أو غير مسجل.'], 404);
        }

        return response()->json($student);
    }
}
