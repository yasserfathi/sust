<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use App\Models\Category;
use App\Models\College;
use App\Models\Page;

class AuthenticatedSessionController extends Controller
{
    public function store(LoginRequest $request)
    {
        $this->ensureIsNotRateLimited($request);

        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey($request));
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey($request));

                $user = Auth::user();
        $isDefault = (strtolower(trim($request->password)) === strtolower(trim($user->email ?? '')) || strtolower(trim($request->password)) === strtolower(trim($user->univ_no ?? '')));
        $user->setAttribute('force_password_change', $isDefault);
        $token = $user->createToken('auth-token')->plainTextToken;
        \Illuminate\Support\Facades\Log::info('LOGIN_DEBUG', [
            'request_password' => $request->password,
            'user_email' => $user->email,
            'user_univ_no' => $user->univ_no,
            'isDefault' => $isDefault
        ]);

        return response()->json([
            'user' => $user,
            'access_token' => $token,
            'message' => 'Login successful',
            'force_password_change' => $isDefault
        ], 200);
    }

    public function destroy(Request $request)
    {
        try {
            if ($request->user()) {
                if ($request->user()->currentAccessToken() && method_exists($request->user()->currentAccessToken(), 'delete')) {
                    $request->user()->currentAccessToken()->delete();
                }
            }

            Auth::guard('web')->logout();

            if ($request->hasSession()) {
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }
        } catch (\Throwable $e) {
            // Log warning or ignore if session/token already expired
        }

        return response()->json(['message' => 'تم تسجيل الخروج بنجاح']);
    }

    public function userPages(Request $request, $page = null)
    {
        if (!$request->user()) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $user = $request->user();

        $filterClosure = function ($q) use ($user) {
            $q->where('active', 1);
            if ($user->role != 1) {
                $q->where(function ($subQ) use ($user) {
                    $subQ->whereHas('categoryPageUsers', fn($sub) => $sub->where('user_id', $user->id));

                    if ($user->role == 2 || $user->staff_latest_by_id) {
                        $subQ->orWhereIn('category_id', function ($query) {
                            $query->select('id')
                                  ->from('categories')
                                  ->where('title', 'like', '%تدريس%')
                                  ->orWhere('title', 'like', '%أعضاء%')
                                  ->orWhere('title', 'like', '%اعضاء%')
                                  ->orWhere('title', 'like', '%هيئة%')
                                  ->orWhere('title', 'like', '%faculty%');
                        });
                    }

                    if ($user->is_college_rep) {
                        $repTitles = [
                            'الألبوم', 'الاخبار باللغة العربية', 'الاخبار باللغة الانجليزية',
                            'الاقسام', 'المستخدمين', 'كلمة عميد الكلية', 'رؤساء الاقسام',
                            'عمداء الكليات', 'الرؤية و الرسالة و الاهداف', 'نشاطات الكلية',
                            'عن الكلية', 'البرامج الأكاديمية', 'الاعلانات باللغة العربية',
                            'الاعلانات باللغة الانجليزية', 'التقويم الدراسي باللغة العربية',
                            'التقويم الدراسي باللغة الانجليزية', 'الورش والمؤتمرات والسمنارات باللغة العربية',
                            'الورش والمؤتمرات والسمنارات باللغة الانجليزية', 'اضافة قسم', 'اضافة مستخدم', 'اضافة رؤساء الاقسام', 'اضافة عمداء الكليات',
                            'اضافة مدرسة', 'اضافة شعبة'
                        ];
                        $repUrls = [
                            '/album', '/news', '/news_en', '/department', '/user', '/dean_word',
                            '/head_of_department', '/dean_of_college', '/college_strategic',
                            '/academic_programs', '/ads', '/ads_en', '/calendar', '/calendar_en',
                            '/workshops', '/workshops_en', '/workshpos', '/workshpos_en',
                            '/school', '/section'
                        ];
                        
                        $collegeId = $user->staff_latest_by_id?->department?->college_id;
                        $collegeName = $collegeId ? College::find($collegeId)?->name : '';
                        if (str_contains($collegeName, 'الشؤون العلمية') || str_contains($collegeName, 'الشئون العلمية')) {
                            $repTitles = array_merge($repTitles, [
                                'المراكز والإدارات و الوحدات', 'عن الشؤون العلمية',
                                'الرؤية و الرسالة و الاهداف و القيم', 'الهيكل التنظيمي لامانة الشؤون العلمية',
                                'الهيكل الوظيفي لامانة الشؤون العلمية', 'مهام أمين أمانة الشؤون العلمية',
                                'مهام نائب امين أمانة الشؤون العلمية', 'المشرف الإداري بالأمانة'
                            ]);
                        }

                        $subQ->orWhereIn('title', $repTitles)
                             ->orWhereIn('url', $repUrls);
                    }
                });
            }
        };

        $query = Category::with(['categoryPage' => $filterClosure])
            ->when($page, fn($q) => $q->whereHas('categoryPage', fn($sub) => $sub->where('url', $page)->where('active', 1)))
            ->whereHas('categoryPage', $filterClosure);

        $items = $query->get();


        if ($user->role == 1) {
            $pagesMap = Page::whereNotNull('category')
                ->where('category', '!=', '')
                ->where('category', '!=', '-')
                ->pluck('category', 'slug');
        } else {
            $userColleges = College::where('user_id', $user->id)->pluck('id');
            $pagesMap = Page::whereIn('college_id', $userColleges)
                ->whereNotNull('category')
                ->where('category', '!=', '')
                ->where('category', '!=', '-')
                ->pluck('category', 'slug');
        }

        $itemsArray = $items->toArray();
        foreach ($itemsArray as &$category) {
            $grouped = [];
            $ungrouped = [];
            foreach ($category['category_page'] as $page) {
                // Remove '/page/' or leading slash if it exists to match the 'title' (slug) in pages table
                $slug = str_replace('/page/', '', $page['url']);
                $slug = ltrim($slug, '/');
                
                $subCat = $pagesMap[$slug] ?? null;
                if ($subCat) {
                    if (!isset($grouped[$subCat])) $grouped[$subCat] = [];
                    $grouped[$subCat][] = $page;
                } else {
                    $ungrouped[] = $page;
                }
            }
            // Custom sort for ungrouped pages to ensure desired order
            usort($ungrouped, function ($a, $b) {
                $order = [
                    '/college' => 1,
                    '/school' => 2,
                    '/department' => 3,
                    '/section' => 4,
                    // University leadership hierarchy order
                    '/page/administration' => 20,
                    '/page/vice_chancellor' => 21,
                    '/page/deputy_vice_chancellor' => 22,
                    '/page/principal' => 23,
                ];
                $weightA = $order[$a['url']] ?? 100 + $a['id'];
                $weightB = $order[$b['url']] ?? 100 + $b['id'];
                return $weightA <=> $weightB;
            });

            $category['grouped_pages'] = (object)$grouped;
            $category['ungrouped_pages'] = $ungrouped;
            unset($category['category_page']);
        }
        unset($category);

        if ($user->role == 1) {
            $adminPages = [
                'title' => 'صفحات مدير النظام',
                'grouped_pages' => [],
                'ungrouped_pages' => [
                    ['title' => 'اضافة فئة', 'url' => '/category'],
                    ['title' => 'اضافة صفحة', 'url' => '/category_page'],
                    ['title' => 'تنسيب الصفحات للمستخدمين', 'url' => '/category_page_user'],
                ],
            ];
            array_unshift($itemsArray, $adminPages);
        }

        return response()->json([
            'success' => !empty($itemsArray),
            'result' => !empty($itemsArray) ? $itemsArray : null,
            'message' => !empty($itemsArray) ? null : 'Not authorized',
        ], !empty($itemsArray) ? 200 : 403);
    }

    // public function userPages($page = null)
    // {
    //     if (!Auth::check()) {
    //         return response()->json(['result' => 'Unauthenticated'], 401);
    //     }

    //     $user = Auth::user();

    //     $result = Category::with('categoryPage')
    //         ->when($page, function ($query, $page) {
    //             $query->whereHas('categoryPage', function ($sub) use ($page) {
    //                 $sub->where('url', $page);
    //             });
    //         })
    //         ->whereHas('categoryPage.categoryPageUsers', function ($query) use ($user) {
    //             if ($user->role != 1) {
    //                 $query->where('user_id', $user->id);
    //             }
    //         });

    //     $adminPages = collect();
    //     if ($user->role == 1) {
    //         $adminPages = collect([
    //             'title' => 'صفحات مدير النظام',
    //             'category_page' => [
    //                 ['title' => 'اضافة فئة', 'url' => '/category'],
    //                 ['title' => 'اضافة صفحة', 'url' => '/category_page'],
    //                 ['title' => 'تنسيب الصفحات للمستخدمين', 'url' => '/category_page_user'],
    //             ],
    //         ]);
    //     }

    //     $items = $result->get();
    //     if ($adminPages->isNotEmpty()) {
    //         $items->prepend($adminPages);
    //     }

    //     return $items->isNotEmpty()
    //         ? response()->json(['result' => $items], 200)
    //         : response()->json(['result' => 'Not authorized'], 403);
    // }

    protected function ensureIsNotRateLimited(Request $request)
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    protected function throttleKey(Request $request)
    {
        return strtolower($request->input('email')) . '|' . $request->ip();
    }
}
