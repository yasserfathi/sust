<?php

namespace App\Http\Controllers;

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

class CollegeController extends Controller
{
    public function index(Request $request, $name)
    {
        $college = $this->getCollege($name);
        $data = $this->getCommonData($college, $name);

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

        return view('college/home', compact("data"));
    }

    public function archive(Request $request, $name)
    {
        $college = $this->getCollege($name);
        $data = $this->getCommonData($college, $name);

        $data['news'] = News::select([
            'id',
            'title',
            'detail_portion',
            'news_date'
        ])
            ->withFirstImage()
            ->where('lang', 2)
            ->where('active', 1)
            ->where('college_id', $college->id)
            ->orderByDesc('id')
            ->paginate(8);

        if ($data['news']->isEmpty()) {
            // abort(404); // Optional: decide if empty archive should 404
        }

        // Add recent news for sidebar
        $data['recent_news'] = $this->getSidebarNews($college->id);

        return view('college/news_archive', ['data' => $data]);
    }

    public function details(Request $request, $name, $slug)
    {
        $college = $this->getCollege($name);
        $data = $this->getCommonData($college, $name);

        $data['news'] = News::select('id', 'title', 'news_date', 'detail', 'file')
            ->with([
                'photos' => function ($query) {
                    $query->select('album_photos.id', 'title', 'img');
                }
            ])
            ->where([
                ['lang', 2],
                ['slug', '=', $slug]
            ])
            ->first();

        // Add recent news for sidebar
        $data['recent_news'] = $this->getSidebarNews($college->id);

        return view('college/news_details', ['data' => $data]);
    }

    /**
     * About Page
     */
    public function about(Request $request, $name)
    {
        return $this->renderPage($name, 'about', 'college/about');
    }

    /**
     * About Scientific Affairs Page
     */
    public function about_scientific_affairs(Request $request, $name)
    {
        return $this->renderPage($name, 'about_scientific_affairs', 'college/about_scientific_affairs');
    }

    /**
     * Generic Dynamic Page
     */
    public function dynamic_page(Request $request, $name, $slug)
    {
        $college = $this->getCollege($name);
        $data = $this->getCommonData($college, $name);

        $data['page'] = Page::select('title', 'detail', 'img')
            ->where('slug', $slug)
            ->where('college_id', $college->id)
            ->where('lang', 2)
            ->first();

        $data['news'] = $this->getMainNews($college->id);
        $data['recent_news'] = $this->getSidebarNews($college->id);

        return view('college.dynamic_page', compact("data"));
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
                // Filter courses by English lang if needed, or get all?
                // Assuming lang=2 is English for courses too, based on other controllers
                $q->where('lang', 2);
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

        return view('college/academic_programs', compact("data"));
    }

    public function about_department(Request $request, $college_name, $dept_name)
    {
        $college = $this->getCollege($college_name);
        $data = $this->getCommonData($college, $college_name);

        // Specific to Academic Programs
        $data['name'] = $college_name;
        $data['banner'] = $college->banner;

        $dept_name_decoded = str_replace('-', ' ', $dept_name);

        $department = Department::select('id', 'name', 'name_en', 'description')
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
        $data['recent_news'] = $data['news'];

        $data['recent_ads'] = $this->getSidebarAds($college->id);

        return view('college/about_department', compact("data"));
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

        return view('college/academic_programs', compact("data"));
    }

    /**
     * Dean Message Page
     */
    public function dean_message(Request $request, $name)
    {
        // Matches $data['dean_message'] in view
        return $this->renderPage($name, 'dean_word', 'college/dean_message');
    }

    /**
     * Activities Page
     */
    public function Activities(Request $request, $name)
    {
        // Matches $data['activities'] in view
        return $this->renderPage($name, 'activities', 'college/activities');
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
            ->where('lang', 2)
            ->first();

        $data['news'] = $this->getMainNews($college->id);
        $data['recent_news'] = $this->getSidebarNews($college->id);


        $data['recent_ads'] = $this->getSidebarAds($college->id);
        return view('college/vision_mission_objectives', compact("data"));
    }

    /**
     * Staff Page
     */
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

        $staffList = StaffEmploy::with(['department:id,name_en,college_id', 'department.college:id,name_en', 'user:id,name,name_en,slug,img,thumb_img'])
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
                    $deanStaff->load(['department:id,name_en,college_id', 'department.college:id,name_en', 'user']);
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
        return view('college/staff', compact("data"));
    }

    /**
     * Key Persons Page
     */
    public function key_persons(Request $request)
    {
        $data = [
            'colleges' => $this->getEntitiesByType('college'),
            'deanships' => $this->getEntitiesByType('deanship'),
            'centers' => $this->getEntitiesByType('center'),
            'recent_news' => $this->getSidebarNews(null),
        ];

        return view('key_persons', compact("data"));
    }
    private function renderPage($slug, $pageTitle, $viewName)
    {
        $college = $this->getCollege($slug);

        $data = $this->getCommonData($college, $slug);

        // Fetch the specific page content (e.g., 'about', 'dean_message')
        $page = Page::select('detail', 'img')
            ->where('lang', 2)
            ->where('slug', $pageTitle)
            ->where('college_id', $college->id)
            ->first();

        if (!$page) {
            $page = new Page();
            $page->detail = '<p>Content coming soon.</p>';
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

        return view($viewName, compact("data"));
    }

    /**
     * Find college by slug or fail
     */
    private function getCollege($slug)
    {
        $college = College::select('id', 'name', 'name_en', 'logo_en', 'banner', 'college_type', 'slug')
            ->where('slug', $slug)
            ->orWhere('slug', 'college-of-' . $slug)
            ->orWhere('slug', $slug . '-college')
            ->first();

        if (!$college) {
            $college = College::select('id', 'name', 'name_en', 'logo_en', 'banner', 'college_type', 'slug')
                ->where('slug', 'like', '%' . $slug . '%')
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

        if (request()->segment(1) && strtolower($collegeTypeEn) !== strtolower(request()->segment(1))) {
            abort(404);
        }

        return $college;
    }

    /**
     * Data shared across all college pages (Logo_en, Name, Departments)
     */
    private function getCommonData($college, $slug)
    {
        return [
            'name' => $slug,
            'college' => $college,
            'college_title' => $college->name_en ?: $college->name,
            'college_type' => $college->getRawOriginal('college_type'),
            'logo_en' => $college->logo_en,
            'banner' => $college->banner,
            'departments' => Department::select('id', 'name_en')
                ->where('active', 1)
                ->where('college_id', $college->id)
                ->get(),
            'dynamic_pages' => Page::select('id', 'title', 'slug')
                ->where('college_id', $college->id)
                ->where('lang', 2)
                ->whereNotIn('title', ['about', 'dean_word', 'activities', 'about_scientific_affairs'])
                ->get()
        ];
    }
    private function getMainNews(int $college_id)
    {
        return News::select('id', 'title', 'slug', 'news_date', 'detail_portion')
            ->with('photos')
            ->where('college_id', $college_id)
            ->where('lang', 2)
            ->orderByRaw('priority desc')
            ->orderByDesc('id')
            ->limit(3)
            ->get();
    }
    private function getSidebarNews($college_id = null)
    {
        $query = News::select('title', 'slug')
            ->where('lang', 2)
            ->orderByRaw('priority desc')
            ->orderByDesc('id')
            ->limit(3);

        if ($college_id) {
            $query->where('college_id', $college_id);
        }

        return $query->get();
    }
    private function getSidebarAds(int $college_id)
    {
        return Ad::select('id', 'title', 'slug')
            ->where('college_id', $college_id)
            ->where('lang', 2)
            ->where('active', 1)
            ->orderByRaw('priority desc')
            ->orderByDesc('id')
            ->limit(3)
            ->get();
    }

    public function ads_archive(Request $request, $name)
    {
        $college = $this->getCollege($name);
        $data = $this->getCommonData($college, $name);

        $data['ads'] = Ad::select([
            'id',
            'title',
            'slug',
            'detail_portion',
            'ad_date'
        ])
            ->withFirstImage()
            ->where('lang', 2)
            ->where('active', 1)
            ->where('college_id', $college->id)
            ->orderByRaw('priority desc')
            ->orderByDesc('id')
            ->paginate(8);

        return view('college/ads_archive', ['data' => $data]);
    }

    public function ads_details(Request $request, $name, $slug)
    {
        $college = $this->getCollege($name);
        $data = $this->getCommonData($college, $name);

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

        return view('college/ads_details', ['data' => $data]);
    }

    private function getEntitiesByType($type)
    {
        return College::select('name_en', 'slug')
            ->where('active', 1)
            ->where('college_type', $type)
            ->get();
    }
}