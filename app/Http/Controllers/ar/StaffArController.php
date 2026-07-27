<?php

namespace App\Http\Controllers\ar;

use App\Http\Controllers\Controller;
use App\Models\StaffAcademic;
use App\Models\Staff_resume;
use App\Models\User;

class StaffArController extends Controller
{
    private $job_titles = [
        'Teaching assistant' => 'مساعد تدريس',
        'Lecturer' => 'محاضر',
        'Assistant Professor' => 'استاذ مساعد',
        'Associate Professor' => 'استاذ مشارك',
        'Professor' => 'استاذ'
    ];

    private function getSharedData()
    {
        return [
            'colleges' => \App\Models\College::select('name', 'name_en', 'slug')->where('active', 1)->where('college_type', 'college')->get(),
            'deanships' => \App\Models\College::select('name', 'name_en', 'slug')->where('active', 1)->where('college_type', 'deanship')->get(),
            'centers' => \App\Models\College::select('name', 'name_en', 'slug')->where('active', 1)->where('college_type', 'center')->get(),
        ];
    }

    public function cv($name_en)
    {
        $userModel = User::where('name_en', $name_en)
            ->with(['staff_latest.department.college', 'staff_latest'])
            ->where('active', 1)
            ->firstOrFail();

        $resumeRecord = Staff_resume::where('user_id', $userModel->id)->first();

        if ($resumeRecord && $resumeRecord->file) {
            $filePath = storage_path('app/public/' . $resumeRecord->file);
            if (file_exists($filePath)) {
                return response()->download($filePath);
            }
        }
        return redirect('#');
    }

    public function show($name_en)
    {
        $userModel = User::where('name_en', $name_en)
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
        return view('ar/staff.index', ['user' => $userModel, 'data' => $data, 'job_titles' => $this->job_titles]);
    }

    public function scientificPapers($name_en)
    {
        $userModel = User::where('name_en', $name_en)->firstOrFail();

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
            'job_titles' => $this->job_titles
        ]);
    }

    public function books($name_en)
    {
        $userModel = User::where('name_en', $name_en)->firstOrFail();

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
            'job_titles' => $this->job_titles
        ]);
    }

    public function runningProjects($name_en)
    {
        $userModel = User::where('name_en', $name_en)->firstOrFail();

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
            'job_titles' => $this->job_titles
        ]);
    }

    public function courses($name_en)
    {
        $userModel = User::where('name_en', $name_en)->firstOrFail();

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
            'job_titles' => $this->job_titles
        ]);
    }

    public function communityService($name_en)
    {
        $userModel = User::where('name_en', $name_en)->firstOrFail();

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
            'job_titles' => $this->job_titles
        ]);
    }

    public function workshops($name_en)
    {
        $userModel = User::where('name_en', $name_en)->firstOrFail();

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
            'job_titles' => $this->job_titles
        ]);
    }

    public function supervisingProjects($name_en)
    {
        $userModel = User::where('name_en', $name_en)->firstOrFail();

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
            'job_titles' => $this->job_titles
        ]);
    }

    public function researchTopics($name_en)
    {
        $userModel = User::where('name_en', $name_en)->firstOrFail();

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
            'job_titles' => $this->job_titles
        ]);
    }

    public function positions($name_en)
    {
        $userModel = User::where('name_en', $name_en)->firstOrFail();

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
            'job_titles' => $this->job_titles
        ]);
    }

    public function committees($name_en)
    {
        $userModel = User::where('name_en', $name_en)->firstOrFail();

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
            'job_titles' => $this->job_titles
        ]);
    }

    public function trainingCourses($name_en)
    {
        $userModel = User::where('name_en', $name_en)->firstOrFail();

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
            'job_titles' => $this->job_titles
        ]);
    }

    public function certificates($name_en)
    {
        $userModel = User::where('name_en', $name_en)->firstOrFail();

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
            'job_titles' => $this->job_titles
        ]);
    }

    public function googleScholar($name_en)
    {
        $userModel = User::where('name_en', $name_en)->firstOrFail();

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
            'job_titles' => $this->job_titles
        ]);
    }

    public function articles($name_en)
    {
        $userModel = User::where('name_en', $name_en)->firstOrFail();

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
            'job_titles' => $this->job_titles
        ]);
    }

    public function links($name_en)
    {
        $userModel = User::where('name_en', $name_en)->firstOrFail();

        $links = StaffAcademic::where('user_id', $userModel->id)
            ->where('item', 'links')
            ->where('lang', '1')
            ->get();

        $userModel->load(['staff_latest.department.college']);

        $data = $this->getSharedData();
        return view('ar/staff.links', [
            'user' => $userModel,
            'links' => $links,
            'data' => $data,
            'job_titles' => $this->job_titles
        ]);
    }
}
