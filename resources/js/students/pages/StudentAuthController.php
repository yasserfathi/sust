<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class StudentAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // 1. Call Moodle API to get the token
        $response = Http::asForm()->post('https://el.sustech.edu/login/token.php', [
            'username' => $request->username,
            'password' => $request->password,
            'service' => 'moodle_mobile_app',
        ]);

        $data = $response->json();

        // 2. If token returned and status 200
        if ($response->successful() && isset($data['token'])) {

            // Insert or Update the student in the database
            $student = Student::firstOrCreate(
                ['username' => $request->username],
                ['name' => $request->username] // Moodle username as default name until synced
            );

            // Update Moodle token
            $student->update(['moodle_token' => $data['token']]);

            // Generate Sanctum token for Vue.js
            $sanctumToken = $student->createToken('student_auth')->plainTextToken;

            return response()->json([
                'access_token' => $sanctumToken,
                'student' => $student,
                'requires_email' => empty($student->email) // Boolean flag for Vue router
            ], 200);
        }

        return response()->json(['message' => 'بيانات الدخول غير صحيحة أو هناك خطأ في الاتصال بالمنصة'], 401);
    }

    // Method to confirm and save the email
    public function confirmEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:students,email,' . $request->user()->id,
        ]);

        $student = $request->user();

        // 1. Save email to database
        $student->email = $request->email;
        $student->email_verified_at = now();
        $student->save();

        // إرسال رسالة تأكيد بسيطة للبريد الإلكتروني للتحقق من عمله
        $emailSent = true;
        try {
            Mail::raw("مرحباً بك {$student->name}،\n\nتم حفظ بريدك الإلكتروني وتحديثه بنجاح في بوابة الطالب.", function ($message) use ($student) {
                $message->to($student->email)
                    ->subject('تأكيد تحديث البريد الإلكتروني - بوابة الطالب');
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Email sending failed: ' . $e->getMessage());
            $emailSent = false;
        }

        // 2. Save email into Moodle (using Moodle REST API)
        // NOTE: core_user_update_users requires an admin-level Moodle web service token.
        /*
        $moodleAdminToken = config('services.moodle.admin_token');
        $moodleApiUrl = 'https://el.sustech.edu/webservice/rest/server.php';

        Http::asForm()->post($moodleApiUrl, [
            'wstoken' => $moodleAdminToken,
            'wsfunction' => 'core_user_update_users',
            'moodlewsrestformat' => 'json',
            'users[0][username]' => $student->username,
            'users[0][email]' => $student->email,
        ]);
        */

        return response()->json([
            'message' => 'تم حفظ البريد الإلكتروني وتحديثه بنجاح',
            'student' => $student,
            'email_sent' => $emailSent
        ]);
    }
}