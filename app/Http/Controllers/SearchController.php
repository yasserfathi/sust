<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\Page;
use App\Models\College;
use App\Models\AcademicProgram;
use App\Models\Workshop;
use App\Models\Calendar;
use App\Models\ViceChancellor;
use App\Models\DeanOfCollege;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('q');

        if (!$keyword) {
            return back()->with('error', 'الرجاء إدخال كلمة للبحث');
        }

        $unified = collect();

        // 1. الكليات والمراكز
        $colleges = College::where('active', 1)
            ->where(function ($query) use ($keyword) {
                $query->where('name', 'LIKE', "%{$keyword}%")
                      ->orWhere('name_en', 'LIKE', "%{$keyword}%");
            })->take(50)->get();

        foreach ($colleges as $item) {
            $unified->push((object)[
                'title_ar' => $item->name,
                'title_en' => $item->name_en ?: $item->name,
                'url_ar' => url('/ar/college/' . $item->slug),
                'url_en' => url('/college/' . $item->slug),
                'desc_ar' => 'صفحة تفاصيل الكلية أو المركز.',
                'desc_en' => 'College or Center details page.',
                'type_ar' => 'كلية / مركز',
                'type_en' => 'College / Center',
                'icon' => 'icofont-university',
                'priority' => 1 // نعطي الكليات أولوية عالية للظهور أولاً
            ]);
        }

        // 2. البرامج الأكاديمية
        $programs = AcademicProgram::where('active', 1)
            ->where(function ($query) use ($keyword) {
                $query->where('program_name', 'LIKE', "%{$keyword}%")
                      ->orWhere('program_name_en', 'LIKE', "%{$keyword}%");
            })->take(100)->get();

        foreach ($programs as $item) {
            $unified->push((object)[
                'title_ar' => $item->program_name,
                'title_en' => $item->program_name_en ?: $item->program_name,
                'url_ar' => '#', // البرامج عادة ليس لها رابط منفصل إلا داخل الكلية
                'url_en' => '#',
                'desc_ar' => 'برنامج أكاديمي متاح للتسجيل.',
                'desc_en' => 'Available academic program.',
                'type_ar' => 'برنامج أكاديمي',
                'type_en' => 'Academic Program',
                'icon' => 'icofont-certificate-alt-1',
                'priority' => 2
            ]);
        }

        // 3. الأخبار
        $news = News::where('active', 1)
            ->where(function ($query) use ($keyword) {
                $query->where('title', 'LIKE', "%{$keyword}%")
                      ->orWhere('detail', 'LIKE', "%{$keyword}%");
            })->latest('news_date')->take(100)->get();

        foreach ($news as $item) {
            $desc = strip_tags($item->detail_portion ?: $item->detail);
            $unified->push((object)[
                'title_ar' => $item->title,
                'title_en' => $item->title,
                'url_ar' => url('/ar/news/details/' . $item->slug),
                'url_en' => url('/news/details/' . $item->slug),
                'desc_ar' => Str::limit($desc, 200),
                'desc_en' => Str::limit($desc, 200),
                'type_ar' => 'أخبار',
                'type_en' => 'News',
                'icon' => 'icofont-newspaper',
                'priority' => 3
            ]);
        }

        // 4. ورش العمل والفعاليات
        $workshops = Workshop::where('active', 1)
            ->where(function ($query) use ($keyword) {
                $query->where('title', 'LIKE', "%{$keyword}%")
                      ->orWhere('detail', 'LIKE', "%{$keyword}%");
            })->latest('workshop_date')->take(50)->get();

        foreach ($workshops as $item) {
            $desc = strip_tags($item->detail_portion ?: $item->detail);
            $unified->push((object)[
                'title_ar' => $item->title,
                'title_en' => $item->title,
                'url_ar' => url('/ar/workshops/details/' . $item->slug),
                'url_en' => url('/workshops/details/' . $item->slug),
                'desc_ar' => Str::limit($desc, 200),
                'desc_en' => Str::limit($desc, 200),
                'type_ar' => 'فعالية / ورشة',
                'type_en' => 'Event / Workshop',
                'icon' => 'icofont-presentation',
                'priority' => 4
            ]);
        }

        // 5. الصفحات الثابتة
        $pages = Page::where(function ($query) use ($keyword) {
                $query->where('title', 'LIKE', "%{$keyword}%")
                      ->orWhere('detail', 'LIKE', "%{$keyword}%");
            })->take(50)->get();

        foreach ($pages as $item) {
            $desc = strip_tags($item->detail_portion ?: $item->detail);
            $unified->push((object)[
                'title_ar' => $item->title,
                'title_en' => $item->title,
                'url_ar' => url('/ar/' . $item->slug),
                'url_en' => url('/' . $item->slug),
                'desc_ar' => Str::limit($desc, 200),
                'desc_en' => Str::limit($desc, 200),
                'type_ar' => 'صفحة',
                'type_en' => 'Page',
                'icon' => 'icofont-page',
                'priority' => 5
            ]);
        }

        // 6. التقويم الأكاديمي
        $calendars = Calendar::where('year', 'LIKE', "%{$keyword}%")->get();
        foreach ($calendars as $item) {
            $url = $item->file ? asset('storage/' . $item->file) : '#';
            $unified->push((object)[
                'title_ar' => 'التقويم الأكاديمي لعام ' . $item->year,
                'title_en' => 'Academic Calendar for ' . $item->year,
                'url_ar' => $url,
                'url_en' => $url,
                'desc_ar' => 'انقر لتحميل وعرض التقويم الأكاديمي التفصيلي.',
                'desc_en' => 'Click to download and view the detailed academic calendar.',
                'type_ar' => 'تقويم أكاديمي',
                'type_en' => 'Academic Calendar',
                'icon' => 'icofont-calendar',
                'priority' => 1
            ]);
        }

        $calendarKeywords = ['امتحان', 'تسجيل', 'موعد', 'تقويم', 'exam', 'registration', 'calendar'];
        $suggestCalendar = false;
        foreach($calendarKeywords as $kw) {
            if (stripos($keyword, $kw) !== false) {
                $suggestCalendar = true; break;
            }
        }
        
        $latestCalendar = null;
        if ($suggestCalendar) {
            $latestCalendar = Calendar::latest('year')->first();
        }

        // 7. شخصيات الإدارة (مدير الجامعة والعمداء)
        $viceChancellors = ViceChancellor::whereHas('user', function ($query) use ($keyword) {
            $query->where('name', 'LIKE', "%{$keyword}%")->orWhere('name_en', 'LIKE', "%{$keyword}%");
        })->with('user')->get();

        foreach ($viceChancellors as $item) {
            if(!$item->user) continue;
            $unified->push((object)[
                'title_ar' => $item->user->name,
                'title_en' => $item->user->name_en ?: $item->user->name,
                'url_ar' => url('/ar/staff/' . $item->user->slug),
                'url_en' => url('/staff/' . $item->user->slug),
                'desc_ar' => 'المنصب: مدير الجامعة',
                'desc_en' => 'Position: Vice Chancellor',
                'type_ar' => 'الإدارة العليا',
                'type_en' => 'Top Administration',
                'icon' => 'icofont-king-crown',
                'priority' => 1
            ]);
        }

        $deans = DeanOfCollege::whereHas('user', function ($query) use ($keyword) {
            $query->where('name', 'LIKE', "%{$keyword}%")->orWhere('name_en', 'LIKE', "%{$keyword}%");
        })->with('user', 'college')->get();

        foreach ($deans as $item) {
            if(!$item->user) continue;
            $collegeNameAr = $item->college ? 'عميد ' . $item->college->name : 'عميد كلية';
            $collegeNameEn = $item->college ? 'Dean of ' . $item->college->name_en : 'College Dean';
            
            $unified->push((object)[
                'title_ar' => $item->user->name,
                'title_en' => $item->user->name_en ?: $item->user->name,
                'url_ar' => url('/ar/staff/' . $item->user->slug),
                'url_en' => url('/staff/' . $item->user->slug),
                'desc_ar' => 'المنصب: ' . $collegeNameAr,
                'desc_en' => 'Position: ' . $collegeNameEn,
                'type_ar' => 'الإدارة والعمداء',
                'type_en' => 'Deans & Administration',
                'icon' => 'icofont-king-crown',
                'priority' => 1
            ]);
        }

        // 8. دليل الأكاديميين والموظفين
        $staff = User::whereHas('staff')
            ->where(function ($query) use ($keyword) {
                $query->where('name', 'LIKE', "%{$keyword}%")->orWhere('name_en', 'LIKE', "%{$keyword}%");
            })->take(50)->get();

        foreach ($staff as $item) {
            $unified->push((object)[
                'title_ar' => $item->name,
                'title_en' => $item->name_en ?: $item->name,
                'url_ar' => url('/ar/staff/' . $item->slug),
                'url_en' => url('/staff/' . $item->slug),
                'desc_ar' => $item->email ? 'البريد الإلكتروني للتواصل: ' . $item->email : 'عضو هيئة التدريس',
                'desc_en' => $item->email ? 'Contact Email: ' . $item->email : 'Academic Staff',
                'type_ar' => 'دليل الأكاديميين',
                'type_en' => 'Staff Directory',
                'icon' => 'icofont-user',
                'priority' => 6
            ]);
        }

        // ترتيب النتائج حسب الأولوية (الكليات والإدارة تظهر قبل الأخبار مثلاً)
        $unified = $unified->sortBy('priority')->values();

        // عملية الـ Pagination اليدوية لـ Collection
        $page = $request->input('page', 1);
        $perPage = 10;
        $sliced = $unified->slice(($page - 1) * $perPage, $perPage)->values();
        
        $paginatedResults = new LengthAwarePaginator($sliced, $unified->count(), $perPage, $page, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);

        return view('search.results', compact('keyword', 'paginatedResults', 'latestCalendar'));
    }
}
