<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\AlbumPhoto;
use App\Models\College;
use App\Models\CollegeStrategic;
use App\Models\AcademicProgram;
use App\Models\Department;
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
        $data['name'] = $name;
        $data['banner'] = $college->banner;
        $data['gallery'] = AlbumPhoto::select('title_en', 'thumb_img', 'img')
            ->where('album_id', 1)
            ->orderBy('id', 'desc')
            ->limit(3)
            ->get();

        $data['news'] = $this->getMainNews($college->id);

        $data['recent_ads'] = Ad::select('title', 'slug')
            ->where('lang', 2)
            ->orderByRaw('priority desc')
            ->orderBy('id', 'desc')
            ->limit(3)
            ->get();

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
            ->orderBy('id', 'desc')
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
            ->firstOrFail();

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
            ->firstOrFail();

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
            ->where('college_id', $college->id)
            ->where('active', 1)
            ->get();

        $data['news'] = $this->getMainNews($college->id);
        $data['recent_news'] = $this->getSidebarNews($college->id);

        $data['recent_ads'] = Ad::select('title', 'slug')
            ->where('lang', 2)
            ->orderByRaw('priority desc')
            ->orderBy('id', 'desc')
            ->limit(3)
            ->get();

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

        $data['description'] = Department::select('name_en', 'description')->where('name_en', $dept_name_decoded)
            ->where('college_id', $college->id)
            ->firstOrFail();

        $data['news'] = $this->getMainNews($college->id);
        $data['recent_news'] = $this->getSidebarNews($college->id);

        $data['recent_ads'] = Ad::select('title', 'slug')
            ->where('lang', 2)
            ->orderByRaw('priority desc')
            ->orderBy('id', 'desc')
            ->limit(3)
            ->get();

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

        $department = Department::where('name_en', $dept_name_decoded)
            ->where('college_id', $college->id)
            ->firstOrFail();

        $data['programs'] = AcademicProgram::where('department_id', $department->id)
            ->where('active', 1)
            ->get();

        $data['news'] = $this->getMainNews($college->id);
        $data['recent_news'] = $this->getSidebarNews($college->id);

        $data['recent_ads'] = Ad::select('title', 'slug')
            ->where('lang', 2)
            ->orderByRaw('priority desc')
            ->orderBy('id', 'desc')
            ->limit(3)
            ->get();

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
            ->firstOrFail();

        $data['news'] = $this->getMainNews($college->id);
        $data['recent_news'] = $this->getSidebarNews($college->id);


        $data['recent_ads'] = Ad::select('title')
            ->where('lang', 2)
            ->orderByRaw('priority desc')
            ->orderBy('id', 'desc')
            ->limit(3)
            ->get();
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

        $data['staff'] = StaffEmploy::with(['department:id,name_en,college_id', 'department.college:id,name_en', 'user'])
            ->whereHas('user', function ($q) {
                $q->where('id', '!=', 1);
            })
            ->whereHas('department', function ($q) use ($college) {
                $q->where('college_id', $college->id);
            })
            ->orderBy('hire_date', 'desc')
            ->get();

        $data['news'] = $this->getMainNews($college->id);
        $data['recent_news'] = $this->getSidebarNews($college->id);


        $data['recent_ads'] = Ad::select('title', 'slug')
            ->where('lang', 2)
            ->orderByRaw('priority desc')
            ->orderBy('id', 'desc')
            ->limit(3)
            ->get();
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

        $data[$pageTitle] = $page;

        $data['news'] = $this->getMainNews($college->id);
        $data['recent_news'] = $this->getSidebarNews($college->id);

        return view($viewName, compact("data"));
    }

    /**
     * Find college by slug or fail
     */
    private function getCollege($slug)
    {
        $college = College::select('id', 'logo', 'banner', 'college_type')
            ->where('slug', $slug)
            ->orWhere('slug', 'college-of-' . $slug)
            ->orWhere('slug', $slug . '-college')
            ->orWhere('slug', 'like', '%' . $slug . '%')
            ->firstOrFail();

        if (strtolower($college->getRawOriginal('college_type')) !== strtolower(request()->segment(1))) {
            abort(404);
        }

        return $college;
    }

    /**
     * Data shared across all college pages (Logo, Name, Departments)
     */
    private function getCommonData($college, $slug)
    {
        return [
            'name' => $slug,
            'college_type' => $college->getRawOriginal('college_type'),
            'logo' => $college->logo,
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
        return News::select('id', 'title', 'news_date', 'detail_portion')
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
            ->orderBy('id', 'desc')
            ->limit(3);

        if ($college_id) {
            $query->where('college_id', $college_id);
        }

        return $query->get();
    }
    private function getEntitiesByType($type)
    {
        return College::select('name_en', 'slug')
            ->where('active', 1)
            ->where('college_type', $type)
            ->get();
    }
}