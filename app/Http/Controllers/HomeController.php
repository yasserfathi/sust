<?php

namespace App\Http\Controllers;

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
				->where('lang', 2)
				->orderBy('priority', 'desc')
				->orderByDesc('id')
				->limit(3)
				->get()
		];
	}
	public function index(Request $request)
	{
		$data = \Illuminate\Support\Facades\Cache::rememberForever(\App\Services\HomeCacheService::CACHE_KEY_HOME_EN, function () {
			$shared = $this->getSharedData();

			// Get featured home gallery photos (up to 5 photos) from CollegeGallery or Album 1
			$galleryRecord = null;
			if (\Illuminate\Support\Facades\Schema::hasTable('college_galleries')) {
				$galleryRecord = CollegeGallery::where('college_id', 1)->first()
					?? CollegeGallery::latest('id')->first();
			}

			if ($galleryRecord && !empty($galleryRecord->photos)) {
				$photoIds = explode(',', $galleryRecord->photos);
				$gallery = AlbumPhoto::select('title_en', 'thumb_img', 'img')
					->whereIn('id', $photoIds)
					->limit(5)
					->get();
			} else {
				$gallery = AlbumPhoto::select('title_en', 'thumb_img', 'img')
					->where('album_id', 1)
					->limit(5)
					->orderByDesc('id')
					->get();
			}

			$ads = Ad::select('id', 'title', 'ad_date', 'detail_portion', 'slug')
				->with('photos')
				->where('lang', 2)
				->where('active', 1)
				->orderByRaw('priority desc')
				->orderByDesc('id')
				->limit(4)
				->get();

			$news = News::select('id', 'title', 'news_date', 'detail_portion', 'slug')
				->with(['photos' => function ($q) {
					$q->select('album_photos.id', 'album_photos.img', 'album_photos.thumb_img', 'album_photos.title_en');
				}])
				->withFirstImage()
				->where('lang', 2)
				->where('active', 1)
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

		return view('home', ['data' => $data]);
	}

	public function administration(Request $request)
	{
		$data = $this->getSharedData();

		$data['administration'] = Page::select('detail', 'img')
			->where('lang', 2)
			->where('slug', 'administration')
			->first();
		return view('administration', compact("data"));
	}


	public function about_sust(Request $request)
	{
		$data = $this->getSharedData();

		$data['about_sust'] = Page::select('detail', 'img')
			->where('lang', 2)
			->where('slug', 'about_sust')
			->first();
		return view('about_sust', compact("data"));
	}

	public function about(Request $request)
	{
		$data = $this->getSharedData();

		$data['about'] = Page::select('detail', 'img')
			->where('lang', 2)
			->where('slug', 'about')
			->first();

		return view('about', compact("data"));
	}

	public function leadership(Request $request)
	{
		$data = $this->getSharedData();

		$data['leadership'] = DeanOfCollege::select('id', 'college_id', 'user_id')
			->with([
				'user:id,name,name_en,img',
				'user.staff_latest',
				'user.staff_latest_by_id',
				'college:id,name,name_en,slug,college_type',
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
				'college:id,name,name_en,slug,college_type',
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

			$title_en = '';
			if (stripos($job, 'Associate Professor') !== false || stripos($job, 'Assistant Professor') !== false || stripos($rank, 'Associate Professor') !== false || stripos($rank, 'Assistant Professor') !== false) {
				$title_en = 'Dr. ';
			} elseif (stripos($job, 'Professor') !== false || stripos($rank, 'Professor') !== false) {
				$title_en = 'Prof. ';
			}
			$key_person->user_title_en = $title_en;

			$type = $key_person->college->getRawOriginal('college_type');
			$entity_en = 'College';
			$position_en = 'Dean';

			if ($type === 'secretariat') {
				$entity_en = 'Secretariat';
				$position_en = 'Secretary';
			}

			$college_name_en = $key_person->college->name_en ?? $key_person->college->name;
			if (stripos(trim($college_name_en), $entity_en) === 0) {
				$display_name = $college_name_en;
			} else {
				$display_name = $entity_en . ' of ' . $college_name_en;
			}

			$key_person->display_name_en = $display_name;
			$key_person->position_en = $position_en;
		}



		if ($data['vice_chancellor']) {
			$vc = $data['vice_chancellor'];
			$vc_staff = $vc->user->staff_latest_by_id ?? $vc->user->staff_latest;
			$vc_job = $vc_staff->grade_en ?? '';
			$vc_rank = $vc_staff->grade_en ?? '';

			$vc_title_en = '';
			if (stripos($vc_job, 'Associate Professor') !== false || stripos($vc_job, 'Assistant Professor') !== false || stripos($vc_rank, 'Associate Professor') !== false || stripos($vc_rank, 'Assistant Professor') !== false) {
				$vc_title_en = 'Dr. ';
			} elseif (stripos($vc_job, 'Professor') !== false || stripos($vc_rank, 'Professor') !== false) {
				$vc_title_en = 'Prof. ';
			}
			$vc->user_title_en = $vc_title_en;
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
			$rank_person->user_title_en = '';
			if ($rank_person->user) {
				$staff = $rank_person->user->staff_latest_by_id ?? $rank_person->user->staff_latest;
				$job = $staff->grade_en ?? '';
				$staff_rank = $staff->grade_en ?? '';

				$title_en = '';
				if (stripos($job, 'Associate Professor') !== false || stripos($job, 'Assistant Professor') !== false || stripos($staff_rank, 'Associate Professor') !== false || stripos($staff_rank, 'Assistant Professor') !== false) {
					$title_en = 'Dr. ';
				} elseif (stripos($job, 'Professor') !== false || stripos($staff_rank, 'Professor') !== false) {
					$title_en = 'Prof. ';
				}
				$rank_person->user_title_en = $title_en;
			}
		}

		$data['university_ranks'] = $data['university_ranks']->sortBy(function ($item) {
			$name = $item->administrativePosition->title_en ?? ($item->administrativePosition->title ?? '');
			
			if (strpos(strtolower($name), 'deputy') !== false || $name === 'نائب المدير') return 1;
			if (strpos(strtolower($name), 'principal') !== false || $name === 'وكيل الجامعة') return 2;
			if (strpos(strtolower($name), 'secretary') !== false || $name === 'أمين الشؤون العلمية') return 3;
			if (strpos(strtolower($name), 'chair') !== false || $name === 'رئيسة كرسي اليونسكو للمرأة والعلوم والتكنولوجيا') return 4;
			
			return 99;
		})->values();

		return view('leadership', compact("data"));
	}

	public function vice_chancellor_message(Request $request)
	{
		$data = $this->getSharedData();


		$data['vice_chancellor_message'] = Page::select('detail', 'img')
			->where('lang', 2)
			->where('slug', 'vice_chancellor_message')
			->first();

		return view('vice_chancellor_message', compact("data"));
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
				'college:id,name,college_type',
			])->whereHas('user', function ($q) {
				$q->where('active', 1);
			})->whereNull('end_date')
			->first();

		if ($data['vice_chancellor'] && $data['vice_chancellor']->user) {
			$vc_staff = $data['vice_chancellor']->user->staff_latest_by_id ?? $data['vice_chancellor']->user->staff_latest;
			$vc_job = $vc_staff ? ($vc_staff->grade_en ?? '') : '';
			$title_en = 'Prof. Dr. ';
			if (stripos($vc_job, 'Associate') !== false || stripos($vc_job, 'Assistant') !== false) {
				$title_en = 'Dr. ';
			} elseif (stripos($vc_job, 'Professor') !== false) {
				$title_en = 'Prof. Dr. ';
			}
			$data['vice_chancellor']->user_title_en = $title_en;
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

			$title_en = '';
			if (stripos($job, 'Associate Professor') !== false || stripos($job, 'Assistant Professor') !== false || stripos($staff_rank, 'Associate Professor') !== false || stripos($staff_rank, 'Assistant Professor') !== false) {
				$title_en = 'Dr. ';
			} elseif (stripos($job, 'Professor') !== false || stripos($staff_rank, 'Professor') !== false) {
				$title_en = 'Prof. ';
			}

			$rank_person->user_title_en = $title_en;
		}

		$data['university_ranks'] = $data['university_ranks']->sortBy(function ($item) {
			$name = $item->administrativePosition->title_en ?? ($item->administrativePosition->title ?? '');
			
			if (strpos(strtolower($name), 'deputy') !== false || $name === 'نائب المدير') return 1;
			if (strpos(strtolower($name), 'principal') !== false || $name === 'وكيل الجامعة') return 2;
			if (strpos(strtolower($name), 'secretary') !== false || $name === 'أمين الشؤون العلمية') return 3;
			if (strpos(strtolower($name), 'chair') !== false || $name === 'رئيسة كرسي اليونسكو للمرأة والعلوم والتكنولوجيا') return 4;
			
			return 99;
		})->values();

		$leaders = DeanOfCollege::select('id', 'college_id', 'user_id')
			->with([
				'user:id,name,name_en,slug,img',
				'user.staff_latest',
				'user.staff_latest_by_id',
				'college:id,name,name_en,college_type',
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

			$title_en = '';
			if (stripos($job, 'Associate Professor') !== false || stripos($job, 'Assistant Professor') !== false || stripos($staff_rank, 'Associate Professor') !== false || stripos($staff_rank, 'Assistant Professor') !== false) {
				$title_en = 'Dr. ';
			} elseif (stripos($job, 'Professor') !== false || stripos($staff_rank, 'Professor') !== false) {
				$title_en = 'Prof. ';
			}
			$leader->user_title_en = $title_en;
		}

		$data['leaders_grouped'] = $leaders->groupBy(function ($item) {
			return $item->college->getRawOriginal('college_type');
		});

		return view('sust_leaders', compact("data"));
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

		return view('former_vice_chancellors', compact("data"));
	}

	public function khartoum_state(Request $request)
	{
		$data = $this->getSharedData();

		$data['khartoum_state'] = Page::select('detail', 'img')
			->where('lang', 2)
			->where('slug', 'khartoum_state')
			->first();

		return view('khartoum_state', compact("data"));
	}

	public function university_campuses(Request $request)
	{
		$data = $this->getSharedData();

		$data['university_campuses'] = Page::select('detail', 'img', 'title')
			->where('lang', 2)
			->whereIn('slug', ['sust_campuses', 'university_campuses'])
			->first() ?? Page::select('detail', 'img', 'title')->whereIn('slug', ['sust_campuses', 'university_campuses'])->first();

		return view('sust_campuses', compact("data"));
	}

	public function sust_mission_vision_goals(Request $request)
	{
		$data = $this->getSharedData();

		$data['sust_mission_vision_goals'] = Page::select('detail', 'img')
			->where('lang', 2)
			->where('slug', 'sust_mission_vision_goals')
			->first();

		return view('sust_mission_vision_goals', compact("data"));
	}

	public function medical_campus(Request $request)
	{
		$data = $this->getSharedData();

		$data['medical_campus'] = Page::select('detail', 'img')
			->where('lang', 2)
			->where('slug', 'medical_campus')
			->first();

		return view('medical_campus', compact("data"));
	}

	public function campus_detail(Request $request, $slug)
	{
		$data = $this->getSharedData();

		$possibleSlugs = [$slug];
		if (!str_ends_with($slug, '_campus')) {
			$possibleSlugs[] = $slug . '_campus';
		}

		$data['page'] = Page::select('title', 'detail', 'img')
			->where('lang', 2)
			->whereIn('slug', $possibleSlugs)
			->first();

		if (!$data['page']) {
			$data['page'] = Page::select('title', 'detail', 'img')
				->whereIn('slug', $possibleSlugs)
				->firstOrFail();
		}

		return view('dynamic_page', compact("data"));
	}

	public function dynamic_page(Request $request, $slug)
	{
		$data = $this->getSharedData();

		$data['page'] = Page::select('title', 'detail', 'img')
			->where('lang', 2)
			->where('slug', $slug)
			->firstOrFail();

		return view('dynamic_page', compact("data"));
	}
}