<?php

namespace App\Http\Controllers\ar;

use App\Http\Controllers\Controller;
use App\Models\College;
use App\Models\StaffAcademic;
use App\Models\Staff_resume;
use App\Models\User;

class StaffArController extends Controller
{
    private $grades = [
        'Teaching assistant' => 'مساعد تدريس',
        'Lecturer' => 'محاضر',
        'Assistant Professor' => 'استاذ مساعد',
        'Associate Professor' => 'استاذ مشارك',
        'Professor' => 'استاذ'
    ];

    private function getSharedData()
    {
        $entities = College::select('id', 'name', 'name_en', 'slug', 'college_type')
            ->where('active', 1)
            ->get();

        return [
            'colleges' => $entities->filter(fn($c) => in_array($c->getRawOriginal('college_type'), ['college']))->where('id', '!=', 1)->values(),
            'deanships' => $entities->filter(fn($c) => in_array($c->getRawOriginal('college_type'), ['deanship']))->values(),
            'centers' => $entities->filter(fn($c) => in_array($c->getRawOriginal('college_type'), ['center', 'institute']))->sortBy(fn($c) => $c->getRawOriginal('college_type'))->values(),
        ];
    }

    public function cv($slug)
    {
        if (strtolower($slug) === 'admin') {
            abort(404);
        }

        $userModel = User::where('slug', $slug)
            ->with(['staff_latest.department.college', 'staff_latest'])
            ->where('active', 1)
            ->firstOrFail();

        $resumeRecord = Staff_resume::where('user_id', $userModel->id)->first();

        if ($resumeRecord) {
            $file = $resumeRecord->file ?: $resumeRecord->file_en;
            if ($file && trim($file) !== '') {
                // Serve directly via asset() without including 'storage/'
                $cleanPath = preg_replace('#^storage/#', '', ltrim($file, '/'));
                return redirect(asset($cleanPath));
            }
        }
        return redirect()->route('staff_home_ar', ['slug' => $slug])->with('error', 'السيرة الذاتية غير متوفرة حالياً.');
    }

    public function show($slug)
    {
        if (strtolower($slug) === 'admin') {
            abort(404);
        }

        $userModel = User::where('slug', $slug)
            ->with(['staff_latest.department.college', 'staff_latest'])
            ->where('active', 1)
            ->firstOrFail();

        // Fetch "About Me" and "Scientific Papers"
        $aboutMe = StaffAcademic::where('user_id', $userModel->id)
            ->where('item', 'about_me')
            ->where('lang', '1')
            ->value('item_val');

        $scientificPapers = StaffAcademic::where('user_id', $userModel->id)
            ->where('item', 'scientific_papers')
            ->where('lang', '1')
            ->get();

        if ($userModel->staff_latest) {
            $userModel->staff_latest->about_me = $aboutMe ?? '';
            $userModel->staff_latest->scientific_papers = $scientificPapers;
        }

        $data = $this->getSharedData();
        return view('ar/staff.index', ['user' => $userModel, 'data' => $data, 'grades' => $this->grades]);
    }

    public function scientificPapers($slug)
    {
                $userModel = User::where('slug', $slug)->firstOrFail();

        $papers = StaffAcademic::where('user_id', $userModel->id)
            ->where('item', 'scientific_papers')
            ->where('lang', '1')
            ->get();

        $userModel->load(['staff_latest.department.college']);

        $data = $this->getSharedData();
        return view('ar/staff.scientific_papers', [
            'user' => $userModel,
            'papers' => $papers,
            'data' => $data,
            'grades' => $this->grades
        ]);
    }

    public function books($slug)
    {
                $userModel = User::where('slug', $slug)->firstOrFail();

        $books = StaffAcademic::where('user_id', $userModel->id)
            ->where('item', 'books')
            ->where('lang', '1')
            ->get();

        $userModel->load(['staff_latest.department.college']);

        $data = $this->getSharedData();
        return view('ar/staff.books', [
            'user' => $userModel,
            'books' => $books,
            'data' => $data,
            'grades' => $this->grades
        ]);
    }

    public function runningProjects($slug)
    {
                $userModel = User::where('slug', $slug)->firstOrFail();

        $projects = StaffAcademic::where('user_id', $userModel->id)
            ->where('item', 'running_projects')
            ->where('lang', '1')
            ->get();

        $userModel->load(['staff_latest.department.college']);

        $data = $this->getSharedData();
        return view('ar/staff.running_projects', [
            'user' => $userModel,
            'projects' => $projects,
            'data' => $data,
            'grades' => $this->grades
        ]);
    }

    public function courses($slug)
    {
                $userModel = User::where('slug', $slug)->firstOrFail();

        $courses = StaffAcademic::where('user_id', $userModel->id)
            ->where('item', 'courses')
            ->where('lang', '1')
            ->get();

        $userModel->load(['staff_latest.department.college']);

        $data = $this->getSharedData();
        return view('ar/staff.courses', [
            'user' => $userModel,
            'courses' => $courses,
            'data' => $data,
            'grades' => $this->grades
        ]);
    }

    public function communityService($slug)
    {
                $userModel = User::where('slug', $slug)->firstOrFail();

        $services = StaffAcademic::where('user_id', $userModel->id)
            ->where('item', 'communityservice')
            ->where('lang', '1')
            ->get();

        $userModel->load(['staff_latest.department.college']);

        $data = $this->getSharedData();
        return view('ar/staff.communityservice', [
            'user' => $userModel,
            'services' => $services,
            'data' => $data,
            'grades' => $this->grades
        ]);
    }

    public function workshops($slug)
    {
                $userModel = User::where('slug', $slug)->firstOrFail();

        $workshops = StaffAcademic::where('user_id', $userModel->id)
            ->where('item', 'workshops')
            ->where('lang', '1')
            ->get();

        $userModel->load(['staff_latest.department.college']);

        $data = $this->getSharedData();
        return view('ar/staff.workshops', [
            'user' => $userModel,
            'workshops' => $workshops,
            'data' => $data,
            'grades' => $this->grades
        ]);
    }

    public function supervisingProjects($slug)
    {
                $userModel = User::where('slug', $slug)->firstOrFail();

        $supervising_projects = StaffAcademic::where('user_id', $userModel->id)
            ->where('item', 'supervising_projects')
            ->where('lang', '1')
            ->get();

        $userModel->load(['staff_latest.department.college']);

        $data = $this->getSharedData();
        return view('ar/staff.supervising_projects', [
            'user' => $userModel,
            'supervising_projects' => $supervising_projects,
            'data' => $data,
            'grades' => $this->grades
        ]);
    }

    public function researchTopics($slug)
    {
                $userModel = User::where('slug', $slug)->firstOrFail();

        $topics = StaffAcademic::where('user_id', $userModel->id)
            ->where('item', 'research_topics')
            ->where('lang', '1')
            ->get();

        $userModel->load(['staff_latest.department.college']);

        $data = $this->getSharedData();
        return view('ar/staff.research_topics', [
            'user' => $userModel,
            'topics' => $topics,
            'data' => $data,
            'grades' => $this->grades
        ]);
    }

    public function positions($slug)
    {
                $userModel = User::where('slug', $slug)->firstOrFail();

        $positions = StaffAcademic::where('user_id', $userModel->id)
            ->where('item', 'positions')
            ->where('lang', '1')
            ->get();

        $userModel->load(['staff_latest.department.college']);

        $data = $this->getSharedData();
        return view('ar/staff.positions', [
            'user' => $userModel,
            'positions' => $positions,
            'data' => $data,
            'grades' => $this->grades
        ]);
    }

    public function committees($slug)
    {
                $userModel = User::where('slug', $slug)->firstOrFail();

        $committees = StaffAcademic::where('user_id', $userModel->id)
            ->where('item', 'committees')
            ->where('lang', '1')
            ->get();

        $userModel->load(['staff_latest.department.college']);

        $data = $this->getSharedData();
        return view('ar/staff.committees', [
            'user' => $userModel,
            'committees' => $committees,
            'data' => $data,
            'grades' => $this->grades
        ]);
    }

    public function trainingCourses($slug)
    {
                $userModel = User::where('slug', $slug)->firstOrFail();

        $training_courses = StaffAcademic::where('user_id', $userModel->id)
            ->where('item', 'training_courses')
            ->where('lang', '1')
            ->get();

        $userModel->load(['staff_latest.department.college']);

        $data = $this->getSharedData();
        return view('ar/staff.training_courses', [
            'user' => $userModel,
            'training_courses' => $training_courses,
            'data' => $data,
            'grades' => $this->grades
        ]);
    }

    public function certificates($slug)
    {
                $userModel = User::where('slug', $slug)->firstOrFail();

        $certificates = StaffAcademic::where('user_id', $userModel->id)
            ->where('item', 'certificates')
            ->where('lang', '1')
            ->get();

        $userModel->load(['staff_latest.department.college']);

        $data = $this->getSharedData();
        return view('ar/staff.certificates', [
            'user' => $userModel,
            'certificates' => $certificates,
            'data' => $data,
            'grades' => $this->grades
        ]);
    }

    public function googleScholar($slug)
    {
                $userModel = User::where('slug', $slug)->firstOrFail();

        $scholar = StaffAcademic::where('user_id', $userModel->id)
            ->where('item', 'google_scholar')
            ->where('lang', '1')
            ->get();

        $userModel->load(['staff_latest.department.college']);

        $data = $this->getSharedData();
        return view('ar/staff.google_scholar', [
            'user' => $userModel,
            'scholar' => $scholar,
            'data' => $data,
            'grades' => $this->grades
        ]);
    }

    public function articles($slug)
    {
                $userModel = User::where('slug', $slug)->firstOrFail();

        $articles = StaffAcademic::where('user_id', $userModel->id)
            ->where('item', 'articles')
            ->where('lang', '1')
            ->get();

        $userModel->load(['staff_latest.department.college']);

        $data = $this->getSharedData();
        return view('ar/staff.articles', [
            'user' => $userModel,
            'articles' => $articles,
            'data' => $data,
            'grades' => $this->grades
        ]);
    }

    public function links($slug)
    {
                $userModel = User::where('slug', $slug)->firstOrFail();

        $links = StaffAcademic::where('user_id', $userModel->id)
            ->where('item', 'links')
            ->where(function($q) {
                $q->where('lang', '1')->orWhereNull('lang')->orWhere('lang', '');
            })
            ->get();

        $userModel->load(['staff_latest.department.college']);

        $data = $this->getSharedData();
        return view('ar/staff.links', [
            'user' => $userModel,
            'links' => $links,
            'data' => $data,
            'grades' => $this->grades
        ]);
    }
}
