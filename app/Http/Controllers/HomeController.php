<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\AlbumPhoto;
use App\Models\College;
use App\Models\DeanOfCollege;
use App\Models\News;
use App\Models\Page;
use App\Models\ViceChancellor;
use Illuminate\Http\Request;

class HomeController extends Controller
{
	private function getSharedData()
	{
		return [
			'colleges' => College::select('name_en', 'slug')->where('active', 1)
				->where('id', '!=', 1)->where('college_type', 'college')->get(),
			'deanships' => College::select('name_en', 'slug')->where('active', 1)->where('college_type', 'deanship')->get(),
			'centers' => College::select('name_en', 'slug')->where('active', 1)->where('college_type', 'center')->get(),
			'recent_news' => News::select('id', 'title', 'slug')
				->where('active', 1)
				->where('lang', 2)
				->orderBy('priority', 'desc')
				->orderBy('id', 'desc')
				->limit(3)
				->get()
		];
	}
	public function index(Request $request)
	{
		$data = $this->getSharedData();

		$data['gallery'] = AlbumPhoto::select('title_en', 'thumb_img', 'img')
			->where('album_id', 1)
			->limit(3)
			->get();

		$ads = Ad::select('id', 'title', 'ad_date', 'detail_portion', 'slug')
			->with('photos')
			->where('lang', 2)
			->orderByRaw('priority desc')
			->orderByDesc('id')
			->limit(4)
			->get();

		$news = News::select('id', 'title', 'news_date', 'detail_portion', 'slug')
			->withFirstImage()
			->where('lang', 2)
			->where('active', 1)
			->orderByRaw('priority desc')
			->orderByDesc('id')
			->limit(3)
			->get();

		$data['ads'] = $ads;
		$data['news'] = $news;

		return view('home', ['data' => $data]);
	}

	public function administration(Request $request)
	{
		$data = $this->getSharedData();

		$data['administration'] = Page::select('detail', 'img')
			->where('lang', 2)
			->where('slug', 'administration')
			->firstorfail();
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
			$job = $staff->job_title_en ?? '';
			$rank = $staff->rank_en ?? '';

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
			$vc_job = $vc_staff->job_title_en ?? '';
			$vc_rank = $vc_staff->rank_en ?? '';

			$vc_title_en = '';
			if (stripos($vc_job, 'Associate Professor') !== false || stripos($vc_job, 'Assistant Professor') !== false || stripos($vc_rank, 'Associate Professor') !== false || stripos($vc_rank, 'Assistant Professor') !== false) {
				$vc_title_en = 'Dr. ';
			} elseif (stripos($vc_job, 'Professor') !== false || stripos($vc_rank, 'Professor') !== false) {
				$vc_title_en = 'Prof. ';
			}
			$vc->user_title_en = $vc_title_en;
		}

		return view('leadership', compact("data"));
	}

	public function vice_chancellor_message(Request $request)
	{
		$data = $this->getSharedData();


		$data['vice_chancellor_message'] = Page::select('detail', 'img')
			->where('lang', 2)
			->where('slug', 'vice_chancellor_message')
			->firstorfail();

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
				'user:id,name_en,img',
				'user.staff_latest:staff_employs.id,staff_employs.user_id',
				'college:id,name',
			])->whereHas('user', function ($q) {
				$q->where('active', 1);
			})->whereNull('end_date')
			->first();

		$leaders = DeanOfCollege::select('id', 'college_id', 'user_id')
			->with([
				'user:id,name_en,img',
				'user.staff_latest:staff_employs.id,staff_employs.user_id',
				'college:id,name,college_type',
			])->whereHas('user', function ($q) {
				$q->where('active', 1);
			})
			->whereHas('college', function ($q) {
				$q->where('active', 1);
			})->whereNull('end_date')
			->orderBy('college_id')->orderByDesc('start_date')->get();

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
			->with('user:id,name,img')
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
			->firstorfail();

		return view('khartoum_state', compact("data"));
	}

	public function university_campuses(Request $request)
	{
		$data = $this->getSharedData();

		$data['university_campuses'] = Page::select('detail', 'img')
			->where('lang', 2)
			->where('slug', 'university_campuses')
			->firstorfail();

		return view('university_campuses', compact("data"));
	}

	public function sust_mission_vision_goals(Request $request)
	{
		$data = $this->getSharedData();

		$data['sust_mission_vision_goals'] = Page::select('detail', 'img')
			->where('lang', 2)
			->where('slug', 'sust_mission_vision_goals')
			->firstorfail();

		return view('sust_mission_vision_goals', compact("data"));
	}

	public function medicale_campus(Request $request)
	{
		$data = $this->getSharedData();

		$data['medicale_campus'] = Page::select('detail', 'img')
			->where('lang', 2)
			->where('slug', 'medicale_campus')
			->firstorfail();

		return view('medicale_campus', compact("data"));
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