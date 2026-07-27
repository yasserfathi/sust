<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use App\Models\Category;

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
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'access_token' => $token,
            'message' => 'Login successful'
        ], 200);
    }

    public function destroy(Request $request)
    {
        if ($request->user() && $request->user()->currentAccessToken()) {
            $request->user()->currentAccessToken()->delete();
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->noContent();
    }

    public function userPages(Request $request, $page = null)
    {
        if (!$request->user()) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $user = $request->user();

        $query = Category::with(['categoryPage' => function ($q) {
            $q->where('active', 1);
        }])
            ->when($page, fn($q) => $q->whereHas('categoryPage', fn($sub) => $sub->where('url', $page)->where('active', 1)))
            ->whereHas('categoryPage', function ($q) use ($user) {
                $q->where('active', 1);
                if ($user->role != 1) {
                    $q->whereHas('categoryPageUsers', fn($sub) => $sub->where('user_id', $user->id));
                }
            });

        $items = $query->get();

        if ($user->role == 1) {
            $pagesMap = \App\Models\Page::whereNotNull('category')
                ->where('category', '!=', '')
                ->where('category', '!=', '-')
                ->pluck('category', 'slug');
        } else {
            $userColleges = \App\Models\College::where('user_id', $user->id)->pluck('id');
            $pagesMap = \App\Models\Page::whereIn('college_id', $userColleges)
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
