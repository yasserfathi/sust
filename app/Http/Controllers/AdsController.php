<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\College;
use App\Models\AlbumPhoto;
use Illuminate\Http\Request;


class AdsController extends Controller
{
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
	public function archive(Request $request)
	{
		$data = $this->getSharedData();

		$data['ads'] = Ad::select([
			'id',
			'title',
			'slug',
			'detail_portion',
			'ad_date',
		])
			->withFirstImage()
			->where('lang', 2)
			->where('active', 1)
			->orderByRaw('priority desc')
			->orderByDesc('id')
			->paginate(8);
		$data['recent_ads'] = Ad::select('id', 'title', 'slug')
			->where('lang', 2)
			->where('active', 1)
			->orderByRaw('priority desc')
			->orderByDesc('id')
			->limit(5)
			->get();

		return view('ads_archive', ['data' => $data]);
	}
	public function details(Request $request, $slug)
	{
		$decoded = urldecode($slug);
		$titleFromSlug = str_replace('-', ' ', $decoded);

		$ad = Ad::select('id', 'title', 'slug', 'ad_date', 'detail', 'file')
			->with('photos')
			->where('lang', 2)
			->where(function ($q) use ($slug, $decoded, $titleFromSlug) {
				$q->where('slug', $slug)
					->orWhere('slug', $decoded)
					->orWhere('title', $decoded)
					->orWhere('title', $titleFromSlug);
			})
			->firstOrFail();

		$data = $this->getSharedData();
		$data['ads'] = $ad;

		$data['recent_ads'] = Ad::select('id', 'title', 'slug')
			->where('lang', 2)
			->where('active', 1)
			->orderByRaw('priority desc')
			->orderByDesc('id')
			->limit(5)
			->get();

		return view('ads_details', ['data' => $data]);
	}
}