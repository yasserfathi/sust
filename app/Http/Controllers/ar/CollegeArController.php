<?php

namespace App\Http\Controllers\ar;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Album;
use App\Models\AlbumPhoto;
use App\Models\College;
use App\Models\CollegeStrategic;
use App\Models\AcademicProgram;
use App\Models\DeanOfCollege;
use App\Models\Department;
use App\Models\HeadOfDepartment;
use App\Models\News;
use App\Models\Page;
use App\Models\StaffEmploy;
use Illuminate\Http\Request;

class CollegeArController extends Controller
{
    /**
     * Home Page
     */
    public function index(Request $request, $college_name)
    {
        $college = $this->getCollege($college_name);
        $data = $this->getCommonData($college, $college_name);

        // Specific to Home
        // Fetch only active gallery photos for this college (up to 5 photos)
        $collegeAlbumIds = Album::where('college_id', $college->id)->pluck('id');
        $data['gallery'] = AlbumPhoto::select('title_en', 'title', 'thumb_img', 'img')
            ->whereIn('album_id', $collegeAlbumIds)
            ->where('is_active', true)
            ->limit(5)
            ->orderByDesc('id')
            ->get();

        if ($data['gallery']->isEmpty()) {
            $galleryRecord = null;
            if (\Illuminate\Support\Facades\Schema::hasTable('college_galleries')) {
                $galleryRecord = \App\Models\CollegeGallery::where('college_id', 1)->first()
                    ?? \App\Models\CollegeGallery::latest('id')->first();
            }

            if ($galleryRecord && !empty($galleryRecord->photos)) {
                $photoIds = explode(',', $galleryRecord->photos);
                $data['gallery'] = AlbumPhoto::select('title_en', 'title', 'thumb_img', 'img')
                    ->whereIn('id', $photoIds)
                    ->limit(5)
                    ->get();
            } else {
                $data['gallery'] = AlbumPhoto::select('title_en', 'title', 'thumb_img', 'img')
                    ->where('album_id', 1)
                    ->limit(5)
                    ->orderByDesc('id')
                    ->get();
            }
        }

        $data['news'] = $this->getMainNews($college->id);


        $data['recent_ads'] = $this->getSidebarAds($college->id);
        return view('ar/college/home', compact("data"));
    }

    /**
     * About Page
     */
    public function about(Request $request, $college_name)
    {
        return $this->renderPage($college_name, 'about', viewName: 'ar/college/about');
    }

    /**
     * About Scientific Affairs Page
     */
    public function about_scientific_affairs(Request $request, $college_name)
    {
        return $this->renderPage($college_name, 'about_scientific_affairs', 'ar/college/about_scientific_affairs');
    }

    /**
     * Generic Dynamic Page
     */
    public function dynamic_page(Request $request, $college_name, $slug)
    {
        $college = $this->getCollege($college_name);
        $data = $this->getCommonData($college, $college_name);

        $data['page'] = Page::select('title', 'detail', 'img')
            ->where('slug', $slug)
            ->where('college_id', $college->id)
            ->where('lang', 1)
            ->first();

        $data['news'] = $this->getMainNews($college->id);
        $data['recent_news'] = $this->getSidebarNews($college->id);

        return view('ar.college.dynamic_page', compact("data"));
    }

    /**
     * Academic Programs Page
     */
    public function academic_programs(Request $request, $college_name)
    {
        $college = $this->getCollege($college_name);
        $data = $this->getCommonData($college, $college_name);

        // Specific to Academic Programs
        $data['name'] = $college_name;
        $data['banner'] = $college->banner;

        $data['programs'] = AcademicProgram::with([
            'courses' => function ($q) {
                // Filter courses by Arabic lang
                $q->where('lang', 1);
            }
        ])
            ->whereHas('department', function ($q) use ($college) {
                $q->where('college_id', $college->id);
            })
            ->where('active', 1)
            ->get();

        $data['news'] = $this->getMainNews($college->id);
        $data['recent_news'] = $this->getSidebarNews($college->id);

        $data['recent_ads'] = $this->getSidebarAds($college->id);

        return view('ar/college/academic_programs', compact("data"));
    }

    public function about_department(Request $request, $college_name, $dept_name)
    {
        $college = $this->getCollege($college_name);
        $data = $this->getCommonData($college, $college_name);

        // Specific to Academic Programs
        $data['name'] = $college_name;
        $data['banner'] = $college->banner;
        $dept_name_decoded = str_replace('-', ' ', $dept_name);

        $department = Department::select('id', 'name', 'name_en', 'description_ar')
            ->where('name_en', $dept_name_decoded)
            ->where('college_id', $college->id)
            ->first();

        $data['description'] = $department;

        // Fetch current Head of Department
        $data['head_of_department'] = null;
        if ($department) {
            $data['head_of_department'] = HeadOfDepartment::with(['user.staff_latest'])
                ->where('department_id', $department->id)
                ->whereNull('end_date')
                ->latest('start_date')
                ->first();
        }

        $data['news'] = $this->getMainNews($college->id);
        $data['recent_news'] = $this->getSidebarNews($college->id);

        $data['recent_ads'] = $this->getSidebarAds($college->id);

        return view('ar/college/about_department', compact("data"));
    }

    /**
     * Dean Message Page
     */
    public function dean_message(Request $request, $college_name)
    {
        return $this->renderPage($college_name, 'dean_word', 'ar/college/dean_message');
    }

    /**
     * Activities Page
     */
    public function Activities(Request $request, $college_name)
    {
        return $this->renderPage($college_name, 'activities', 'ar/college/activities');
    }

    /**
     * Vision Mission Objectives Page
     */
    public function vision_mission_objectives(Request $request, $college_name)
    {
        $college = $this->getCollege($college_name);
        $data = $this->getCommonData($college, $college_name);

        // Specific to Home
        $data['name'] = $college_name;
        $data['banner'] = $college->banner;
        $data['vision_mission_objectives'] = CollegeStrategic::select('vision', 'mission', 'goals')
            ->where('college_id', $college->id)
            ->where('lang', 1)
            ->first();

        $data['news'] = $this->getMainNews($college->id);
        $data['recent_news'] = $this->getSidebarNews($college->id);


        $data['recent_ads'] = $this->getSidebarAds($college->id);
        return view('ar/college/vision_mission_objectives', compact("data"));
    }

    public function department_programs(Request $request, $college_name, $dept_name)
    {
        $college = $this->getCollege($college_name);
        $data = $this->getCommonData($college, $college_name);

        // Specific to Academic Programs
        $data['name'] = $college_name;
        $data['banner'] = $college->banner;
        $dept_name_decoded = str_replace('-', ' ', $dept_name);

        $department = Department::with(['academicPrograms' => fn($q) => $q->where('active', 1)])
            ->where('college_id', $college->id)
            ->where(function($q) use ($dept_name_decoded) {
                $q->where('name_en', $dept_name_decoded)
                  ->orWhere('name', $dept_name_decoded);
            })
            ->first();

        if (!$department) {
            $department = Department::with(['academicPrograms' => fn($q) => $q->where('active', 1)])
                ->where('college_id', $college->id)
                ->first();
        }

        $data['department'] = $department;
        $data['programs'] = $department ? $department->academicPrograms : collect();

        $data['news'] = $this->getMainNews($college->id);
        $data['recent_news'] = $data['news'];

        $data['recent_ads'] = $this->getSidebarAds($college->id);

        return view('ar/college/academic_programs', compact("data"));
    }

    public function staff(Request $request, $college_name)
    {
        $college = $this->getCollege($college_name);
        $data = $this->getCommonData($college, $college_name);

        // Specific to Staff

        $data['name'] = $college_name;
        $data['banner'] = $college->banner;

        $deanRecord = DeanOfCollege::with(['user.staff_latest.department.college'])
            ->where('college_id', $college->id)
            ->whereNull('end_date')
            ->first();

        $staffList = StaffEmploy::with(['department:id,name,college_id', 'department.college:id,name', 'user:id,name,name_en,slug,img,thumb_img'])
            ->whereHas('user', function ($q) {
                $q->where('id', '!=', 1);
            })
            ->whereHas('department', function ($q) use ($college) {
                $q->where('college_id', $college->id);
            })
            ->orderByRaw("FIELD(grade, 'مساعد تدريس', 'مساعد تدريس ج', 'محاضر', 'استاذ مساعد', 'استاذ مشارك', 'استاذ', 'الاستاذ') DESC")
            ->orderBy('hire_date', 'desc')
            ->get();

        if ($deanRecord && $deanRecord->user) {
            $deanUserId = $deanRecord->user_id;

            $deanStaffKey = $staffList->search(function ($item) use ($deanUserId) {
                return $item->user_id == $deanUserId;
            });

            if ($deanStaffKey !== false) {
                $deanStaff = $staffList->pull($deanStaffKey);
            } else {
                $deanStaff = $deanRecord->user->staff_latest_by_id ?? $deanRecord->user->staff_latest;
                if ($deanStaff) {
                    $deanStaff->load(['department:id,name,college_id', 'department.college:id,name', 'user']);
                }
            }

            if ($deanStaff) {
                $deanStaff->is_dean = true;
                $staffList->prepend($deanStaff);
            }
        }

        $data['staff'] = $staffList;

        $data['news'] = $this->getMainNews($college->id);
        $data['recent_news'] = $this->getSidebarNews($college->id);


        $data['recent_ads'] = $this->getSidebarAds($college->id);
        return view('ar/college/staff', compact("data"));
    }

    private function renderPage($college_name, $pageTitle, $viewName)
    {
        $college = $this->getCollege($college_name);
        $data = $this->getCommonData($college, $college_name);

        // Fetch the specific page content
        $page = Page::select('detail', 'img')
            ->where('lang', 1)
            ->where('slug', $pageTitle)
            ->where('college_id', $college->id)
            ->first();

        if (!$page) {
            $page = new Page();
            $page->detail = '<p>المحتوى قريبا.</p>';
            $page->img = 'images/gallery/vision.jpg';
        }

        if ($pageTitle === 'dean_word') {
            $dean = DeanOfCollege::with('user:id,img')
                ->where('college_id', $college->id)
                ->whereNull('end_date')
                ->first();
            if ($dean && $dean->user && $dean->user->img) {
                $page->img = $dean->user->img;
            }
        }

        $data[$pageTitle] = $page;

        $data['news'] = $this->getMainNews($college->id);
        $data['recent_news'] = $data['news'];

        $data['recent_ads'] = $this->getSidebarAds($college->id);
        return view($viewName, compact("data"));
    }

    /**
     * Find college by slug or fail
     */
    private function getCollege($college_name)
    {
        $college = College::select('id', 'name', 'name_en', 'logo', 'banner', 'college_type', 'slug')
            ->where('slug', $college_name)
            ->orWhere('slug', 'college-of-' . $college_name)
            ->orWhere('slug', $college_name . '-college')
            ->first();

        if (!$college) {
            $college = College::select('id', 'name', 'name_en', 'logo', 'banner', 'college_type', 'slug')
                ->where('slug', 'like', '%' . $college_name . '%')
                ->first();
        }

        if (!$college) {
            abort(404);
        }

        $rawType = $college->getRawOriginal('college_type');
        $collegeTypeEn = array_search($rawType, $college->getCollegeTypeOptions());
        if (!$collegeTypeEn) {
            $collegeTypeEn = $rawType;
        }

        if (request()->segment(2) && strtolower($collegeTypeEn) !== strtolower(request()->segment(2))) {
            abort(404);
        }

        return $college;
    }

    /**
     * Data shared across all college pages (Logo, Name, Departments)
     */
    private function getCommonData($college, $college_name)
    {
        return [
            'name' => $college_name,
            'college' => $college,
            'college_title' => $college->name,
            'college_type' => $college->getRawOriginal('college_type'),
            'logo' => $college->logo,
            'banner' => $college->banner,
            'departments' => Department::select('id', 'name', 'name_en')
                ->where('active', 1)
                ->where('college_id', $college->id)
                ->get(),
            'dynamic_pages' => Page::select('id', 'title', 'slug')
                ->where('college_id', $college->id)
                ->where('lang', 1)
                ->whereNotIn('title', ['about', 'dean_word', 'activities', 'about_scientific_affairs'])
                ->get()
        ];
    }

    private function getMainNews(int $college_id)
    {
        return News::select('id', 'title', 'slug', 'news_date', 'detail_portion')
            ->with('photos')
            ->where('college_id', $college_id)
            ->where('lang', 1)
            ->orderByRaw('priority desc')
            ->orderByDesc('id')
            ->limit(3)
            ->get();
    }

    private function getSidebarNews($college_id)
    {
        return News::select('title', 'slug')
            ->where('college_id', $college_id)
            ->where('lang', 1)
            ->orderByRaw('priority desc')
            ->orderByDesc('id')
            ->limit(3)
            ->get();
    }

    private function getSidebarAds(int $college_id)
    {
        return Ad::select('id', 'title', 'slug')
            ->where('college_id', $college_id)
            ->where('lang', 1)
            ->where('active', 1)
            ->orderByRaw('priority desc')
            ->orderByDesc('id')
            ->limit(3)
            ->get();
    }

    public function news_archive(Request $request, $college_name)
    {
        $college = $this->getCollege($college_name);
        $data = $this->getCommonData($college, $college_name); // Add shared data for layout

        $data['news'] = News::select([
            'id',
            'title',
            'slug', // Added slug
            'detail_portion',
            'news_date'
        ])
            ->withFirstImage()
            ->where('lang', 1)
            ->where('active', 1)
            ->where('college_id', $college->id)
            ->orderByRaw('priority desc')
            ->orderByDesc('id')
            ->paginate(8);

        if ($data['news']->isEmpty()) {
            // Optional: Don't abort, let view handle empty state, or keep abort logic
            // abort(404);
        }

        return view('ar/college/news_archive', ['data' => $data]);
    }

    public function news_details(Request $request, $college_name, $slug)
    {
        $college = $this->getCollege($college_name);
        $data = $this->getCommonData($college, $college_name);

        $data['news'] = News::select('id', 'title', 'news_date', 'detail', 'file')
            ->with('photos')
            ->where('slug', $slug)
            ->first();

        return view('ar/college/news_details', ['data' => $data]);
    }

    public function ads_archive(Request $request, $college_name)
    {
        $college = $this->getCollege($college_name);
        $data = $this->getCommonData($college, $college_name);

        $data['ads'] = Ad::select([
            'id',
            'title',
            'slug',
            'detail_portion',
            'ad_date'
        ])
            ->withFirstImage()
            ->where('lang', 1)
            ->where('active', 1)
            ->where('college_id', $college->id)
            ->orderByRaw('priority desc')
            ->orderByDesc('id')
            ->paginate(8);

        return view('ar/college/ads_archive', ['data' => $data]);
    }

    public function ads_details(Request $request, $college_name, $slug)
    {
        $college = $this->getCollege($college_name);
        $data = $this->getCommonData($college, $college_name);

        $slug_decoded = str_replace('-', ' ', $slug);
        $data['ads'] = Ad::select('id', 'title', 'slug', 'ad_date', 'detail', 'file')
            ->with('photos')
            ->where(function ($query) use ($slug, $slug_decoded) {
                $query->where('slug', $slug)
                    ->orWhere('slug', $slug_decoded)
                    ->orWhere('title', $slug)
                    ->orWhere('title', $slug_decoded);
            })
            ->where(function ($q) use ($college) {
                $q->where('college_id', $college->id)
                  ->orWhereNull('college_id');
            })
            ->first();

        $data['recent_ads'] = $this->getSidebarAds($college->id);
        $data['recent_news'] = $this->getSidebarNews($college->id);

        return view('ar/college/ads_details', ['data' => $data]);
    }
}