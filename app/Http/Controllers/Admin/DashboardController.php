<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\College;
use App\Models\Department;
use App\Models\StaffAcademic;
use App\Models\News;
use App\Models\Ad;

class DashboardController extends Controller
{
    private $colorPalette = [
        '#d65440', '#2563eb', '#10b981', '#f59e0b', '#8b5cf6', 
        '#06b6d4', '#ec4899', '#64748b', '#059669', '#d97706', 
        '#4f46e5', '#db2777', '#0284c7', '#7c3aed', '#475569'
    ];

    public function stats(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $sections = [];

        // 1. Check for ADMIN role
        if ($user->role == 1) {
            $sections[] = $this->getAdminSection();
        }

        // 2. Check for DELEGATE role
        if ($user->is_college_rep) {
            $collegeId = $user->staff_latest_by_id?->department?->college_id;
            if ($collegeId) {
                $sections[] = $this->getDelegateSection($collegeId);
            }
        }

        // 3. Check for FACULTY role
        if ($user->role == 2 || $user->staff_latest_by_id) {
            $sections[] = $this->getFacultySection($user);
        }

        return response()->json(['sections' => $sections]);
    }

    private function getAdminSection()
    {
        $section = [
            'role' => 'admin',
            'title' => 'إحصائيات مدير النظام',
            'cards' => [],
            'charts' => []
        ];

        // ------------------ CARDS ------------------
        $counts = Cache::remember('dashboard_admin_counts', now()->addMinutes(60), function() {
            return College::selectRaw("
                SUM(college_type = 'college') AS colleges_count,
                SUM(college_type IN ('center', 'institute')) AS centers_count,
                SUM(college_type = 'deanship') AS deanships_count
            ")->first();
        });

        $userCount = Cache::remember('dashboard_admin_users', now()->addMinutes(60), fn() => User::count());
        $newsCount = Cache::remember('dashboard_admin_news', now()->addMinutes(60), fn() => News::count());
        $adsCount = Cache::remember('dashboard_admin_ads', now()->addMinutes(60), fn() => Ad::count());
        $staffCount = Cache::remember('dashboard_admin_staff', now()->addMinutes(60), fn() => User::whereHas('staff')->count());

        $section['cards'] = [
            ['label' => 'الكليات', 'value' => (int) $counts->colleges_count, 'icon' => 'mdi-bank', 'color' => '#2563eb', 'gradient' => '135deg, #1e293b, #334155'],
            ['label' => 'المراكز والمعاهد', 'value' => (int) $counts->centers_count, 'icon' => 'mdi-domain', 'color' => '#059669', 'gradient' => '135deg, #1e293b, #334155'],
            ['label' => 'أعضاء هيئة التدريس', 'value' => $staffCount, 'icon' => 'mdi-account-tie', 'color' => '#d65440', 'gradient' => '135deg, #1e293b, #334155'],
            ['label' => 'إجمالي المستخدمين', 'value' => $userCount, 'icon' => 'mdi-account-group', 'color' => '#d97706', 'gradient' => '135deg, #1e293b, #334155'],
            ['label' => 'الأخبار المنشورة', 'value' => $newsCount, 'icon' => 'mdi-newspaper-variant-outline', 'color' => '#475569', 'gradient' => '135deg, #1e293b, #334155'],
            ['label' => 'الإعلانات', 'value' => $adsCount, 'icon' => 'mdi-bullhorn-outline', 'color' => '#7c3aed', 'gradient' => '135deg, #1e293b, #334155'],
        ];

        // ------------------ CHARTS ------------------
        
        // Chart 1: Structural Distribution (Pie)
        $section['charts'][] = [
            'type' => 'pie',
            'title' => 'التوزيع الهيكلي للجامعة',
            'labels' => ['الكليات', 'المراكز والمعاهد', 'العمادات'],
            'data' => [(int) $counts->colleges_count, (int) $counts->centers_count, (int) $counts->deanships_count],
            'colors' => ['#3b82f6', '#10b981', '#ec4899']
        ];

        // Chart 2: Academic Activities Distribution (Doughnut)
        $activities = Cache::remember('dashboard_admin_activities', now()->addMinutes(60), function() {
            return StaffAcademic::select('item', DB::raw('count(*) as count'))->groupBy('item')->get();
        });
        
        $section['charts'][] = [
            'type' => 'doughnut',
            'title' => 'توزيع الأنشطة الأكاديمية العام',
            'labels' => $activities->pluck('item')->map(fn($i) => $this->translateItem($i)),
            'data' => $activities->pluck('count'),
            'colors' => $this->colorPalette
        ];

        // Chart 3: Top 5 Active Colleges (Bar)
        $topColleges = Cache::remember('dashboard_admin_top_colleges', now()->addMinutes(60), function() {
            return News::select('college_id', DB::raw('count(*) as news_count'))
                ->with('college:id,name')
                ->whereNotNull('college_id')
                ->groupBy('college_id')
                ->orderBy('news_count', 'desc')
                ->limit(5)
                ->get();
        });

        $section['charts'][] = [
            'type' => 'bar',
            'title' => 'أكثر الكليات نشاطاً (حسب الأخبار المنشورة)',
            'labels' => $topColleges->pluck('college.name'),
            'data' => $topColleges->pluck('news_count'),
            'colors' => '#d65440'
        ];

        return $section;
    }

    private function getDelegateSection($collegeId)
    {
        $college = College::find($collegeId);
        
        $section = [
            'role' => 'delegate',
            'title' => 'إحصائيات: ' . ($college ? $college->name : 'الكلية'),
            'cards' => [],
            'charts' => []
        ];
        
        // ------------------ CARDS ------------------
        $deptCount = Cache::remember('dash_del_depts_'.$collegeId, now()->addMinutes(60), fn() => Department::where('college_id', $collegeId)->count());
        $staffCount = Cache::remember('dash_del_staff_'.$collegeId, now()->addMinutes(60), fn() => User::whereHas('staff', fn($q) => $q->whereHas('department', fn($q2) => $q2->where('college_id', $collegeId)))->count());
        $newsCount = Cache::remember('dash_del_news_'.$collegeId, now()->addMinutes(60), fn() => News::where('college_id', $collegeId)->count());
        $adsCount = Cache::remember('dash_del_ads_'.$collegeId, now()->addMinutes(60), fn() => Ad::where('college_id', $collegeId)->count());

        $section['cards'] = [
            ['label' => 'الأقسام', 'value' => $deptCount, 'icon' => 'mdi-domain', 'color' => '#1B5E20', 'gradient' => 'to right, #1B5E20, #43A047'],
            ['label' => 'هيئة التدريس بالكلية', 'value' => $staffCount, 'icon' => 'mdi-account-tie', 'color' => '#01579B', 'gradient' => 'to right, #01579B, #039BE5'],
            ['label' => 'أخبار الكلية', 'value' => $newsCount, 'icon' => 'mdi-newspaper', 'color' => '#4A148C', 'gradient' => 'to right, #4A148C, #7B1FA2'],
            ['label' => 'إعلانات الكلية', 'value' => $adsCount, 'icon' => 'mdi-bullhorn', 'color' => '#E65100', 'gradient' => 'to right, #E65100, #F57C00'],
        ];

        // ------------------ CHARTS ------------------
        
        // Chart 1: Staff Distribution by Department (Bar)
        $deptStaff = Cache::remember('dash_del_chart_deptstaff_'.$collegeId, now()->addMinutes(60), function() use ($collegeId) {
            return Department::where('college_id', $collegeId)
                ->withCount(['staff' => fn($q) => $q->whereHas('user')])
                ->having('staff_count', '>', 0)
                ->get();
        });

        if ($deptStaff->count() > 0) {
            $section['charts'][] = [
                'type' => 'bar',
                'title' => 'توزيع أعضاء هيئة التدريس على الأقسام',
                'labels' => $deptStaff->pluck('name'),
                'data' => $deptStaff->pluck('staff_count'),
                'colors' => '#8E44AD'
            ];
        }

        // Chart 2: Academic Activities in College (Doughnut)
        $activities = Cache::remember('dash_del_chart_act_'.$collegeId, now()->addMinutes(60), function() use ($collegeId) {
            return StaffAcademic::select('item', DB::raw('count(*) as count'))
                ->whereHas('user.staff', fn($q) => $q->whereHas('department', fn($q2) => $q2->where('college_id', $collegeId)))
                ->groupBy('item')->get();
        });

        if ($activities->count() > 0) {
            $section['charts'][] = [
                'type' => 'doughnut',
                'title' => 'الأنشطة الأكاديمية الخاصة بالكلية',
                'labels' => $activities->pluck('item')->map(fn($i) => $this->translateItem($i)),
                'data' => $activities->pluck('count'),
                'colors' => $this->colorPalette
            ];
        }

        return $section;
    }

    private function getFacultySection($user)
    {
        $section = [
            'role' => 'faculty',
            'title' => 'تحليل الأداء الأكاديمي',
            'cards' => [],
            'charts' => []
        ];
        
        $activities = Cache::remember('dash_fac_act_'.$user->id, now()->addMinutes(60), function() use ($user) {
            return StaffAcademic::select('item', DB::raw('count(*) as count'))
                ->where('user_id', $user->id)->groupBy('item')->get();
        });
        
        // ------------------ CARDS ------------------
        $totalActivities = $activities->sum('count');
        $papers = $activities->firstWhere('item', 'scientific_papers')?->count ?? 0;
        $books = $activities->firstWhere('item', 'books')?->count ?? 0;
        $courses = $activities->firstWhere('item', 'courses')?->count ?? 0;

        $section['cards'] = [
            ['label' => 'إجمالي الإسهامات', 'value' => $totalActivities, 'icon' => 'mdi-star-circle', 'color' => '#BF360C', 'gradient' => 'to right, #BF360C, #F4511E'],
            ['label' => 'الأوراق العلمية', 'value' => $papers, 'icon' => 'mdi-file-document-outline', 'color' => '#0D47A1', 'gradient' => 'to right, #0D47A1, #1976D2'],
            ['label' => 'الكتب المنشورة', 'value' => $books, 'icon' => 'mdi-book-open', 'color' => '#004D40', 'gradient' => 'to right, #004D40, #00796B'],
            ['label' => 'المقررات الدراسية', 'value' => $courses, 'icon' => 'mdi-school', 'color' => '#3E2723', 'gradient' => 'to right, #3E2723, #5D4037'],
        ];

        // ------------------ CHARTS ------------------
        if ($activities->count() > 0) {
            // Chart 1: Radar Chart for Activity Typology
            $section['charts'][] = [
                'type' => 'polarArea',
                'title' => 'مؤشر التنوع الأكاديمي',
                'labels' => $activities->pluck('item')->map(fn($i) => $this->translateItem($i)),
                'data' => $activities->pluck('count'),
                'colors' => array_slice($this->colorPalette, 0, $activities->count())
            ];
            
            // Chart 2: Detailed Bar Chart
            $section['charts'][] = [
                'type' => 'bar',
                'title' => 'تفصيل الإسهامات الأكاديمية',
                'labels' => $activities->pluck('item')->map(fn($i) => $this->translateItem($i)),
                'data' => $activities->pluck('count'),
                'colors' => '#F39C12'
            ];
        }

        return $section;
    }

    private function translateItem($item) {
        $map = [
            'books' => 'الكتب وفصول من كتاب',
            'scientific_papers' => 'الأوراق العلمية',
            'running_projects' => 'المشاريع البحثية الجارية',
            'courses' => 'المقررات الدراسية',
            'links' => 'روابط مهمة',
            'communityservice' => 'خدمة المجتمع',
            'workshops' => 'الورش والمؤتمرات والسمنارات',
            'supervising_projects' => 'الاشراف على مشاريع بحثية',
            'research_topics' => 'مشاريع بحثية',
            'positions' => 'المناصب الادارية',
            'committees' => 'اللجان والجمعيات',
            'training_courses' => 'الدورات التدريبية',
            'awards' => 'الجوائز والشهادات التقديرية',
            'google_scholar' => 'رابط الباحث العلمي قوقل',
            'articles' => 'مقالات علمية',
            'staff_resume' => 'السيرة الذاتية',
            'staff_album_photos' => 'معرض الصور'
        ];
        return $map[$item] ?? $item;
    }
}
