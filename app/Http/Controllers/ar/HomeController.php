<?php

namespace App\Http\Controllers\ar;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\AlbumPhoto;
use App\Models\College;
use App\Models\CollegeGallery;
use App\Models\DeanOfCollege;
use App\Models\News;
use App\Models\Page;
use App\Models\HeadAdministrativePosition;
use App\Models\ViceChancellor;
use Illuminate\Http\Request;

class HomeController extends Controller
{
	private function getSharedData()
	{
		$entities = \Illuminate\Support\Facades\Cache::rememberForever(\App\Services\HomeCacheService::CACHE_KEY_SHARED_COLLEGES, function () {
			return College::select('id', 'name', 'name_en', 'slug', 'college_type')
				->where('active', 1)
				->get();
		});

		return [
			'colleges' => $entities->filter(fn($c) => in_array($c->getRawOriginal('college_type'), ['college']))->where('id', '!=', 1)->values(),
			'deanships' => $entities->filter(fn($c) => in_array($c->getRawOriginal('college_type'), ['deanship']))->values(),
			'centers' => $entities->filter(fn($c) => in_array($c->getRawOriginal('college_type'), ['center', 'institute']))->sortBy(fn($c) => $c->getRawOriginal('college_type'))->values(),
			'recent_news' => News::select('id', 'title', 'slug')
				->where('active', 1)
				->where('lang', 1)
				->orderBy('priority', 'desc')
				->orderByDesc('id')
				->limit(3)
				->get()
		];
	}
	public function index(Request $request)
	{
		$data = \Illuminate\Support\Facades\Cache::rememberForever(\App\Services\HomeCacheService::CACHE_KEY_HOME_AR, function () {
			$shared = $this->getSharedData();

			// Get featured home gallery photos (up to 5 photos) from CollegeGallery or Album 1
			$galleryRecord = null;
			if (\Illuminate\Support\Facades\Schema::hasTable('college_galleries')) {
				$galleryRecord = CollegeGallery::where('college_id', 1)->first()
					?? CollegeGallery::latest('id')->first();
			}

			if ($galleryRecord && !empty($galleryRecord->photos)) {
				$photoIds = explode(',', $galleryRecord->photos);
				$gallery = AlbumPhoto::select('title', 'thumb_img', 'img')
					->whereIn('id', $photoIds)
					->limit(5)
					->get();
			} else {
				$gallery = AlbumPhoto::select('title', 'thumb_img', 'img')
					->where('album_id', 1)
					->limit(5)
					->orderByDesc('id')
					->get();
			}

			$ads = Ad::select('id', 'title', 'ad_date', 'detail_portion', 'slug')
				->with('photos')
				->where('lang', 1)
				->where('active', 1)
				->orderByRaw('priority desc')
				->orderByDesc('id')
				->limit(4)
				->get();

			$news = News::select('id', 'title', 'news_date', 'detail_portion', 'slug')
				->with(['photos' => function ($q) {
					$q->select('album_photos.id', 'album_photos.img', 'album_photos.thumb_img', 'album_photos.title');
				}])
				->withFirstImage()
				->where('active', 1)
				->where('lang', 1)
				->orderByRaw('priority desc')
				->orderByDesc('id')
				->limit(3)
				->get();

			return array_merge($shared, [
				'gallery' => $gallery,
				'ads' => $ads,
				'news' => $news,
			]);
		});

		return view('ar/home', ['data' => $data]);
	}

	public function administration(Request $request)
	{
		$data = $this->getSharedData();

		$data['administration'] = Page::select('detail', 'img')
			->where('lang', 1)
			->where('slug', 'administration')
			->first();
		return view('ar/administration', compact("data"));
	}

	public function about_sust(Request $request)
	{
		$data = $this->getSharedData();

		$data['about_sust'] = Page::select('detail', 'img')
			->where('lang', 1)
			->where('slug', 'about_sust')
			->first();
		return view('ar/about_sust', compact("data"));
	}


	public function about(Request $request)
	{
		$data = $this->getSharedData();

		$data['about'] = Page::select('detail', 'img')
			->where('lang', 1)
			->where('slug', 'about')
			->first();

		return view('ar/about', compact("data"));
	}
	public function leadership(Request $request)
	{
		$data = $this->getSharedData();

		$data['leadership'] = DeanOfCollege::select('id', 'college_id', 'user_id')
			->with([
				'user:id,name,name_en,img',
				'user.staff_latest',
				'user.staff_latest_by_id',
				'college:id,name,slug,college_type',
			])->whereHas('user', function ($q) {
				$q->where('active', 1);
			})
			->whereHas('college', function ($q) {
				$q->where('active', 1);
			})->whereNull('end_date')
			->orderBy('college_id')->orderByDesc('start_date')->get();

		$data['vice_chancellor'] = ViceChancellor::select('id', 'college_id', 'user_id')
			->with([
				'user:id,name,name_en,img',
				'user.staff_latest',
				'user.staff_latest_by_id',
				'college:id,name,slug,college_type',
			])->whereHas('user', function ($q) {
				$q->where('active', 1);
			})
			->whereHas('college', function ($q) {
				$q->where('active', 1);
			})->whereNull('end_date')
			->orderBy('college_id')->orderByDesc('start_date')->first();

		// Process Leadership Data
		foreach ($data['leadership'] as $key_person) {
			$staff = $key_person->user->staff_latest_by_id ?? $key_person->user->staff_latest;
			$job = $staff->grade_en ?? '';
			$rank = $staff->grade_en ?? '';

			$title_ar = '';
			if (stripos($job, 'Associate Professor') !== false || stripos($job, 'Assistant Professor') !== false || stripos($rank, 'Associate Professor') !== false || stripos($rank, 'Assistant Professor') !== false) {
				$title_ar = 'دكتور/ ';
			} elseif (stripos($job, 'Professor') !== false || stripos($rank, 'Professor') !== false) {
				$title_ar = 'بروفيسور/ ';
			}
			$key_person->user_title_ar = $title_ar;

			$type = $key_person->college->getRawOriginal('college_type');
			$position_ar = 'عميد';
			if ($type === 'secretariat') {
				$position_ar = 'أمين';
			}
			$key_person->position_ar = $position_ar;
		}



		if ($data['vice_chancellor']) {
			$vc_staff = $data['vice_chancellor']->user->staff_latest_by_id ?? $data['vice_chancellor']->user->staff_latest;
			$vc_job = $vc_staff->grade_en ?? '';
			$vc_rank = $vc_staff->grade_en ?? '';

			$vc_title_ar = '';
			if (stripos($vc_job, 'Associate Professor') !== false || stripos($vc_job, 'Assistant Professor') !== false || stripos($vc_rank, 'Associate Professor') !== false || stripos($vc_rank, 'Assistant Professor') !== false) {
				$vc_title_ar = 'دكتور/ ';
			} elseif (stripos($vc_job, 'Professor') !== false || stripos($vc_rank, 'Professor') !== false) {
				$vc_title_ar = 'بروفيسور/ ';
			}
			$data['vice_chancellor']->user_title_ar = $vc_title_ar;
		}

		$data['university_ranks'] = HeadAdministrativePosition::select('id', 'administrative_positions_id', 'college_id', 'user_id', 'start_date', 'end_date')
			->with([
				'user:id,name,name_en,img',
				'user.staff_latest',
				'user.staff_latest_by_id',
				'administrativePosition'
			])->whereHas('user', function ($q) {
				$q->where('active', 1);
			})->whereNull('end_date')
			->orderBy('id')->get();

		foreach ($data['university_ranks'] as $rank_person) {
			$staff = $rank_person->user->staff_latest_by_id ?? $rank_person->user->staff_latest;
			$job = $staff->grade_en ?? '';
			$staff_rank = $staff->grade_en ?? '';

			$title_ar = '';
			if (stripos($job, 'Associate Professor') !== false || stripos($job, 'Assistant Professor') !== false || stripos($staff_rank, 'Associate Professor') !== false || stripos($staff_rank, 'Assistant Professor') !== false) {
				$title_ar = 'دكتور/ ';
			} elseif (stripos($job, 'Professor') !== false || stripos($staff_rank, 'Professor') !== false) {
				$title_ar = 'بروفيسور/ ';
			}
			$rank_person->user_title_ar = $title_ar;
		}

		$data['university_ranks'] = $data['university_ranks']->sortBy(function ($item) {
			$name = $item->administrativePosition->title ?? ($item->administrativePosition->title_en ?? '');
			
			if (strpos(strtolower($name), 'نائب') !== false || strpos(strtolower($name), 'deputy') !== false) return 1;
			if (strpos(strtolower($name), 'وكيل') !== false || strpos(strtolower($name), 'principal') !== false) return 2;
			if (strpos(strtolower($name), 'أمين') !== false || strpos(strtolower($name), 'secretary') !== false) return 3;
			if (strpos(strtolower($name), 'كرسي') !== false || strpos(strtolower($name), 'chair') !== false) return 4;
			
			return 99;
		})->values();

		return view('ar/leadership', compact("data"));
	}

	public function vice_chancellor_message(Request $request)
	{
		$data = $this->getSharedData();

		$data['vice_chancellor_message'] = Page::select('detail', 'img')
			->where('lang', 1)
			->where('slug', 'vice_chancellor_message')
			->first();
		return view('ar/vice_chancellor_message', compact("data"));
	}


	public function sust_leaders(Request $request)
	{
		$data = $this->getSharedData();

		$data['sust_leaders'] = Page::select('detail', 'img')
			->where('slug', 'sust_leaders')
			->first();

		$data['vice_chancellor'] = ViceChancellor::select('id', 'college_id', 'user_id')
			->with([
				'user:id,name,name_en,slug,img',
				'user.staff_latest',
				'user.staff_latest_by_id',
				'college:id,name',
			])->whereHas('user', function ($q) {
				$q->where('active', 1);
			})->whereNull('end_date')
			->first();

		if ($data['vice_chancellor'] && $data['vice_chancellor']->user) {
			$vc_staff = $data['vice_chancellor']->user->staff_latest_by_id ?? $data['vice_chancellor']->user->staff_latest;
			$vc_job = $vc_staff ? ($vc_staff->grade_en ?? '') : '';
			$title_ar = 'بروفيسور/ ';
			if (stripos($vc_job, 'Associate') !== false || stripos($vc_job, 'Assistant') !== false) {
				$title_ar = 'دكتور/ ';
			} elseif (stripos($vc_job, 'Professor') !== false) {
				$title_ar = 'بروفيسور/ ';
			}
			$data['vice_chancellor']->user_title_ar = $title_ar;
		}

		$data['university_ranks'] = HeadAdministrativePosition::select('id', 'administrative_positions_id', 'college_id', 'user_id', 'start_date', 'end_date')
			->with([
				'user:id,name,name_en,slug,img',
				'user.staff_latest',
				'user.staff_latest_by_id',
				'administrativePosition'
			])->whereHas('user', function ($q) {
				$q->where('active', 1);
			})->whereNull('end_date')
			->orderBy('id')->get();

		foreach ($data['university_ranks'] as $rank_person) {
			$staff = null;
			if ($rank_person->user) {
				$staff = $rank_person->user->staff_latest_by_id ?? $rank_person->user->staff_latest;
			}
			$job = $staff ? ($staff->grade_en ?? '') : '';
			$staff_rank = $staff ? ($staff->grade_en ?? '') : '';

			$title_ar = '';
			if (stripos($job, 'Associate Professor') !== false || stripos($job, 'Assistant Professor') !== false || stripos($staff_rank, 'Associate Professor') !== false || stripos($staff_rank, 'Assistant Professor') !== false) {
				$title_ar = 'دكتور/ ';
			} elseif (stripos($job, 'Professor') !== false || stripos($staff_rank, 'Professor') !== false) {
				$title_ar = 'بروفيسور/ ';
			}
			$rank_person->user_title_ar = $title_ar;
		}

		$data['university_ranks'] = $data['university_ranks']->sortBy(function ($item) {
			$name = $item->administrativePosition->title ?? ($item->administrativePosition->title_en ?? '');
			
			if (strpos(strtolower($name), 'نائب') !== false || strpos(strtolower($name), 'deputy') !== false) return 1;
			if (strpos(strtolower($name), 'وكيل') !== false || strpos(strtolower($name), 'principal') !== false) return 2;
			if (strpos(strtolower($name), 'أمين') !== false || strpos(strtolower($name), 'secretary') !== false) return 3;
			if (strpos(strtolower($name), 'كرسي') !== false || strpos(strtolower($name), 'chair') !== false) return 4;
			
			return 99;
		})->values();

		$leaders = DeanOfCollege::select('id', 'college_id', 'user_id')
			->with([
				'user:id,name,name_en,slug,img',
				'user.staff_latest',
				'user.staff_latest_by_id',
				'college:id,name,college_type',
			])->whereHas('user', function ($q) {
				$q->where('active', 1);
			})
			->whereHas('college', function ($q) {
				$q->where('active', 1);
			})->whereNull('end_date')
			->orderBy('college_id')->orderByDesc('start_date')->get();

		foreach ($leaders as $leader) {
			$staff = $leader->user->staff_latest_by_id ?? $leader->user->staff_latest;
			$job = $staff ? ($staff->grade_en ?? '') : '';
			$staff_rank = $staff ? ($staff->grade_en ?? '') : '';

			$title_ar = '';
			if (stripos($job, 'Associate Professor') !== false || stripos($job, 'Assistant Professor') !== false || stripos($staff_rank, 'Associate Professor') !== false || stripos($staff_rank, 'Assistant Professor') !== false) {
				$title_ar = 'دكتور/ ';
			} elseif (stripos($job, 'Professor') !== false || stripos($staff_rank, 'Professor') !== false) {
				$title_ar = 'بروفيسور/ ';
			}
			$leader->user_title_ar = $title_ar;
		}

		$data['leaders_grouped'] = $leaders->groupBy(function ($item) {
			return $item->college->getRawOriginal('college_type');
		});

		return view('ar/sust_leaders', compact("data"));
	}

	public function former_vice_chancellors(Request $request)
	{
		$data = $this->getSharedData();
		$data['banner'] = url('/images/gallery/vision.jpg'); // Fallback banner

		$data['vice_chancellors'] = ViceChancellor::select('id', 'user_id', 'start_date', 'end_date')
			->with('user:id,name,name_en,img')
			->whereNotNull('end_date')
			->orderBy('start_date', 'asc')
			->get();

		return view('ar/former_vice_chancellors', compact("data"));
	}

	public function khartoum_state(Request $request)
	{
		$data = $this->getSharedData();

		$data['khartoum_state'] = Page::select('detail', 'img')
			->where('lang', 1)
			->where('slug', 'khartoum_state')
			->first();
		return view('ar/khartoum_state', compact("data"));
	}

	public function university_campuses(Request $request)
	{
		$data = $this->getSharedData();

		$data['university_campuses'] = Page::select('detail', 'img', 'title')
			->whereIn('slug', ['sust_campuses', 'university_campuses'])
			->first();

		return view('ar/sust_campuses', compact("data"));
	}

	public function sust_mission_vision_goals(Request $request)
	{
		$data = $this->getSharedData();

		$data['sust_mission_vision_goals'] = Page::select('detail', 'img')
			->where('lang', 1)
			->where('slug', 'sust_mission_vision_goals')
			->first();
		return view('ar/sust_mission_vision_goals', compact("data"));
	}

	public function medical_campus(Request $request)
	{
		$data = $this->getSharedData();

		$data['medical_campus'] = Page::select('detail', 'img')
			->where('lang', 1)
			->where('slug', 'medical_campus')
			->first();
		return view('ar/medical_campus', compact("data"));
	}

	public function campus_detail(Request $request, $slug)
	{
		$data = $this->getSharedData();

		// Check with given slug or slug with _campus suffix (e.g. western -> western_campus)
		$possibleSlugs = [$slug];
		if (!str_ends_with($slug, '_campus')) {
			$possibleSlugs[] = $slug . '_campus';
		}

		$data['page'] = Page::select('title', 'detail', 'img')
			->where('lang', 1)
			->whereIn('slug', $possibleSlugs)
			->first();

		if (!$data['page']) {
			// Fallback: try finding without lang constraint or abort 404
			$data['page'] = Page::select('title', 'detail', 'img')
				->whereIn('slug', $possibleSlugs)
				->firstOrFail();
		}

		return view('ar.dynamic_page', compact("data"));
	}

	public function dynamic_page(Request $request, $slug)
	{
		$data = $this->getSharedData();

		$data['page'] = Page::select('title', 'detail', 'img')
			->where('lang', 1)
			->where('slug', $slug)
			->firstOrFail();

		return view('ar.dynamic_page', compact("data"));
	}
}
