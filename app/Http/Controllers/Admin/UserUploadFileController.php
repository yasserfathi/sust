<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaffEmploy;
use App\Models\User;
use App\Models\Department;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Rap2hpoutre\FastExcel\FastExcel;

class UserUploadFileController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls'
        ]);

        $filePath = '';
        if ($request->hasFile('file')) {
            if ($request->file('file')->isValid()) {
                try {
                    $file = $request->file('file');
                    $extension = $file->getClientOriginalExtension();
                    $filename = 'users-upload-list.' . $extension;
                    $file->storeAs('files', $filename, 'public');
                    $filePath = public_path('files/' . $filename);
                } catch (Exception $e) {
                }
            }
        }

        if (!$filePath || !file_exists($filePath)) {
            return response()->json(['message' => 'Error uploading file', 'status' => 400], 400);
        }

        $records = (new FastExcel)->import($filePath);
        $firstRow = $records->first();
        if (!$firstRow) {
            return response()->json(['message' => 'الملف فارغ أو لا يمكن قراءته.', 'status' => 400], 400);
        }

        // Helper to remove all unicode whitespace/control chars from start/end
        $trimUnicode = function($str) {
            return preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', (string)$str);
        };

        // Validate Headers
        $fileColumns = array_map($trimUnicode, array_keys($firstRow));
        
        $acceptedColumns = [
            'رقم الجامعي', 'الرقم الجامعي', 'رقم جامعي',
            'الاسم', 
            'الاسم بالانجليزي', 'الاسم بالإنجليزي',
            'الكلية',
            'القسم',
            'الدرجة العلمية',
            'المسمى الوظيفي',
            'البريد الإلكتروني', 'البريد الالكتروني', 'الايميل', 'الإيميل'
        ];

        // Ensure semantic required columns are present (at least one valid variation for each)
        $hasUnivNo = in_array('رقم الجامعي', $fileColumns) || in_array('الرقم الجامعي', $fileColumns) || in_array('رقم جامعي', $fileColumns);
        $hasName = in_array('الاسم', $fileColumns);
        $hasNameEn = in_array('الاسم بالانجليزي', $fileColumns) || in_array('الاسم بالإنجليزي', $fileColumns);
        $hasEmail = in_array('البريد الإلكتروني', $fileColumns) || in_array('البريد الالكتروني', $fileColumns) || in_array('الايميل', $fileColumns) || in_array('الإيميل', $fileColumns);
        $hasCollege = in_array('الكلية', $fileColumns);
        $hasDept = in_array('القسم', $fileColumns);
        $hasDegree = in_array('الدرجة العلمية', $fileColumns);
        $hasTitle = in_array('المسمى الوظيفي', $fileColumns);

        $missingSemantic = [];
        if (!$hasUnivNo) $missingSemantic[] = 'الرقم الجامعي';
        if (!$hasName) $missingSemantic[] = 'الاسم';
        if (!$hasNameEn) $missingSemantic[] = 'الاسم بالانجليزي';
        if (!$hasEmail) $missingSemantic[] = 'البريد الإلكتروني';
        if (!$hasCollege) $missingSemantic[] = 'الكلية';
        if (!$hasDept) $missingSemantic[] = 'القسم';
        if (!$hasDegree) $missingSemantic[] = 'الدرجة العلمية';
        if (!$hasTitle) $missingSemantic[] = 'المسمى الوظيفي';

        if (count($missingSemantic) > 0) {
            return response()->json([
                'message' => 'يوجد أعمدة مفقودة في الملف: ' . implode('، ', $missingSemantic),
                'accepted_columns' => ['الرقم الجامعي', 'الاسم', 'الاسم بالانجليزي', 'الكلية', 'القسم', 'الدرجة العلمية', 'المسمى الوظيفي', 'البريد الإلكتروني'],
                'status' => 422
            ], 422);
        }

        // Check for invalid columns
        $invalidColumns = [];
        foreach ($fileColumns as $col) {
            if ($col !== '' && !in_array($col, $acceptedColumns)) {
                $invalidColumns[] = $col;
            }
        }
        if (count($invalidColumns) > 0) {
            return response()->json([
                'message' => 'يوجد أعمدة غير معتمدة في الملف: ' . implode('، ', $invalidColumns),
                'accepted_columns' => ['الرقم الجامعي', 'الاسم', 'الاسم بالانجليزي', 'الكلية', 'القسم', 'الدرجة العلمية', 'المسمى الوظيفي', 'البريد الإلكتروني'],
                'status' => 422
            ], 422);
        }

        $success_records = [];
        $failed_records = [];

        foreach ($records as $originalRecord) {
            $record = [];
            foreach ($originalRecord as $key => $value) {
                $record[$trimUnicode($key)] = $value;
            }

            $email = isset($record['البريد الإلكتروني']) ? $trimUnicode($record['البريد الإلكتروني']) : (isset($record['البريد الالكتروني']) ? $trimUnicode($record['البريد الالكتروني']) : (isset($record['الايميل']) ? $trimUnicode($record['الايميل']) : (isset($record['الإيميل']) ? $trimUnicode($record['الإيميل']) : '')));
            $univNo = isset($record['الرقم الجامعي']) ? $trimUnicode($record['الرقم الجامعي']) : (isset($record['رقم الجامعي']) ? $trimUnicode($record['رقم الجامعي']) : (isset($record['رقم جامعي']) ? $trimUnicode($record['رقم جامعي']) : ''));
            $name = isset($record['الاسم']) ? $trimUnicode($record['الاسم']) : '';
            
            if ($email != '' && $univNo != '') {
                try {
                    $user = User::where('univ_no', $univNo)->first();
                    
                    if (!$user) {
                        // Check if email already exists to prevent unique constraint violation
                        if (User::where('email', $email)->exists()) {
                            $failed_records[] = ['name' => $name, 'univ_no' => $univNo, 'reason' => 'البريد الإلكتروني مستخدم مسبقاً لمستخدم آخر'];
                            continue;
                        }
                        
                        $user = new User;
                        $user->univ_no = $univNo;
                        $user->password = Hash::make($email);
                        $user->created_at = \Carbon\Carbon::now()->toDateTimeString();
                        $user->role = 3;
                        $user->active = 1;
                        $user->auth_id = $request->user()->id;
                        $user->phone = '';
                        $user->img = '';
                    } else {
                        // Prevent changing email to an already taken email by another user
                        if ($user->email != $email && User::where('email', $email)->exists()) {
                            $failed_records[] = ['name' => $name, 'univ_no' => $univNo, 'reason' => 'البريد الإلكتروني الجديد الذي تحاول تحديثه مستخدم مسبقاً لمستخدم آخر'];
                            continue;
                        }
                    }

                    $user->name = $name;
                    $user->name_en = isset($record["الاسم بالانجليزي"]) ? $trimUnicode($record["الاسم بالانجليزي"]) : (isset($record["الاسم بالإنجليزي"]) ? $trimUnicode($record["الاسم بالإنجليزي"]) : '');
                    $user->email = $email;
                    $user->save();

                    $departmentName = isset($record['القسم']) ? $trimUnicode($record['القسم']) : '';
                    $department = null;
                    if ($departmentName != '') {
                        $department = Department::where('name', $departmentName)->first();
                        
                        if (!$department) {
                            $failed_records[] = ['name' => $name, 'univ_no' => $univNo, 'reason' => 'القسم (' . $departmentName . ') غير مسجل مسبقاً في النظام'];
                            continue;
                        }
                    }

                    $staff_employs = StaffEmploy::where('user_id', $user->id)->first();
                    if (!$staff_employs) {
                        $staff_employs = new StaffEmploy;
                        $staff_employs->user_id = $user->id;
                        $staff_employs->auth_id = $request->user()->id;
                        $staff_employs->hire_date = '01/01/2000';
                        $staff_employs->specialty = '';
                        $staff_employs->subspecialty = '';
                        $staff_employs->specialty_en = '';
                        $staff_employs->subspecialty_en = '';
                        $staff_employs->created_at = \Carbon\Carbon::now()->toDateTimeString();
                    }

                    $staff_employs->department_id = $department ? $department->id : null;
                    $staff_employs->job_title = isset($record['المسمى الوظيفي']) ? $trimUnicode($record['المسمى الوظيفي']) : '';
                    $staff_employs->rank = isset($record['الدرجة العلمية']) ? $trimUnicode($record['الدرجة العلمية']) : '';
                    $staff_employs->save();

                    $success_records[] = ['name' => $name, 'univ_no' => $univNo];

                } catch (Exception $e) {
                    $failed_records[] = ['name' => $name, 'univ_no' => $univNo, 'reason' => 'خطأ أثناء حفظ البيانات: ' . $e->getMessage()];
                }
            } else {
                $failed_records[] = ['name' => $name, 'univ_no' => $univNo, 'reason' => 'بيانات مفقودة (رقم الجامعي أو البريد الإلكتروني غير متوفر)'];
            }
        }

        return response()->json([
            'message' => 'تمت معالجة الملف',
            'success_records' => $success_records,
            'failed_records' => $failed_records,
            'status' => 201
        ]);
    }
}
