<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\StudentRegistrationMail;
use App\Mail\StudentResetPasswordMail;
use Illuminate\Support\Str;

class StudentAuthController extends Controller
{
    /**
     * LOGIN (bcrypt only)
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $student = Student::where('username', $request->username)->first();

        $isValidPassword = false;

        if ($student) {
            try {
                $isValidPassword = Hash::check($request->password, $student->password);
            } catch (\Exception $e) {
                $isValidPassword = false;
            }
        }

        if (!$isValidPassword) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // revoke old tokens
        $student->tokens()->delete();

        // create token
        $token = $student->createToken('auth-token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'user' => [
                'id' => $student->id,
                'username' => $student->username,
                'email' => $student->email,
                'name' => $student->name,
            ],
        ]);
    }

    /**
     * REGISTER (no cache)
     */
    public function register(Request $request)
    {
        $request->validate([
            'university_number' => 'required|string|unique:students,username',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
        ]);

        $tempStudent = (object) [
            'name' => $request->name,
            'username' => $request->university_number,
        ];

        // send email (password is university number or generated externally)
        Mail::to($request->email)->send(
            new StudentRegistrationMail($tempStudent, $request->university_number)
        );

        return response()->json([
            'message' => 'Check your email to complete registration'
        ]);
    }

    /**
     * SET PASSWORD (bcrypt only)
     */
    public function setupPassword(Request $request)
    {
        $request->validate([
            'university_number' => 'required|exists:students,username',
            'password' => 'required|min:8|confirmed',
        ]);

        $student = Student::where('username', $request->university_number)->first();

        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        $student->password = Hash::make($request->password);
        $student->save();

        return response()->json([
            'message' => 'Password set successfully'
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $student = Student::where('email', $request->email)->first();

        if (!$student) {
            return response()->json(['message' => 'البريد الإلكتروني غير مسجل في النظام'], 404);
        }

        $token = Str::random(60);

        // TODO: Save this token to your password_resets table to verify it later
        // \Illuminate\Support\Facades\DB::table('password_resets')->updateOrInsert(['email' => $request->email], ['token' => Hash::make($token), 'created_at' => now()]);

        Mail::to($student->email)->send(new StudentResetPasswordMail($student, $token));

        return response()->json(['message' => 'تم إرسال رابط استعادة كلمة المرور إلى بريدك الإلكتروني']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
            'token' => 'required'
        ]);

        // TODO: Implement actual password reset logic
        return response()->json(['message' => 'تم تغيير كلمة المرور بنجاح']);
    }
}