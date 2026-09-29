<?php

namespace App\Http\Controllers;

use App\Models\{College, StaffAcademic, Staff_resume, User};

class StaffController extends Controller
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
        $entities = College::select('id', 'name_en', 'slug', 'college_type')
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
            $file = $resumeRecord->file_en ?: $resumeRecord->file;
            if ($file && trim($file) !== '') {
                // Serve directly via asset() without including 'storage/'
                $cleanPath = preg_replace('#^storage/#', '', ltrim($file, '/'));
                return redirect(asset($cleanPath));
            }
        }
        return redirect()->route('staff_home', ['slug' => $slug])->with('error', 'The CV file is not available.');
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
            ->where('lang', '2')
            ->value('item_val');

        $scientificPapers = StaffAcademic::where('user_id', $userModel->id)
            ->where('item', 'scientific_papers')
            ->where('lang', '2')
            ->get();

        if ($userModel->staff_latest) {
            $userModel->staff_latest->about_me = $aboutMe ?? '';
            $userModel->staff_latest->scientific_papers = $scientificPapers;
        }

        $data = $this->getSharedData();
        return view('staff.index', ['user' => $userModel, 'data' => $data, 'grades' => $this->grades]);
    }

    public function scientificPapers($slug)
    {
                $userModel = User::where('slug', $slug)->firstOrFail();

        $papers = StaffAcademic::where('user_id', $userModel->id)
            ->where('item', 'scientific_papers')
            ->where('lang', '2')
            ->get();

        $userModel->load(['staff_latest.department.college']);

        $data = $this->getSharedData();
        return view('staff.scientific_papers', [
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
            ->where('lang', '2')
            ->get();

        $userModel->load(['staff_latest.department.college']);

        $data = $this->getSharedData();
        return view('staff.books', [
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
            ->where('lang', '2')
            ->get();

        $userModel->load(['staff_latest.department.college']);

        $data = $this->getSharedData();
        return view('staff.running_projects', [
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
            ->where('lang', '2')
            ->get();

        $userModel->load(['staff_latest.department.college']);

        $data = $this->getSharedData();
        return view('staff.courses', [
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
            ->where('lang', '2')
            ->get();

        $userModel->load(['staff_latest.department.college']);

        $data = $this->getSharedData();
        return view('staff.communityservice', [
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
            ->where('lang', '2')
            ->get();

        $userModel->load(['staff_latest.department.college']);

        $data = $this->getSharedData();
        return view('staff.workshops', [
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
            ->where('lang', '2')
            ->get();

        $userModel->load(['staff_latest.department.college']);

        $data = $this->getSharedData();
        return view('staff.supervising_projects', [
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
            ->where('lang', '2')
            ->get();

        $userModel->load(['staff_latest.department.college']);

        $data = $this->getSharedData();
        return view('staff.research_topics', [
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
            ->where('lang', '2')
            ->get();

        $userModel->load(['staff_latest.department.college']);

        $data = $this->getSharedData();
        return view('staff.positions', [
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
            ->where('lang', '2')
            ->get();

        $userModel->load(['staff_latest.department.college']);

        $data = $this->getSharedData();
        return view('staff.committees', [
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
            ->where('lang', '2')
            ->get();

        $userModel->load(['staff_latest.department.college']);

        $data = $this->getSharedData();
        return view('staff.training_courses', [
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
            ->where('lang', '2')
            ->get();

        $userModel->load(['staff_latest.department.college']);

        $data = $this->getSharedData();
        return view('staff.certificates', [
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
            ->where('lang', '2')
            ->get();

        $userModel->load(['staff_latest.department.college']);

        $data = $this->getSharedData();
        return view('staff.google_scholar', [
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
            ->where('lang', '2')
            ->get();

        $userModel->load(['staff_latest.department.college']);

        $data = $this->getSharedData();
        return view('staff.articles', [
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
                $q->where('lang', '2')->orWhereNull('lang')->orWhere('lang', '');
            })
            ->get();

        $userModel->load(['staff_latest.department.college']);

        $data = $this->getSharedData();
        return view('staff.links', [
            'user' => $userModel,
            'links' => $links,
            'data' => $data,
            'grades' => $this->grades
        ]);
    }
}
