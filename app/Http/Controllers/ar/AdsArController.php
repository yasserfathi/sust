<?php

namespace App\Http\Controllers\ar;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\College;
use App\Models\AlbumPhoto;
use Illuminate\Http\Request;


class AdsArController extends Controller
{
	private function getSharedData()
	{
		return [
			'colleges' => College::select('name', 'name_en', 'slug')->where('active', 1)->where('college_type', 'college')->get(),
			'deanships' => College::select('name', 'name_en', 'slug')->where('active', 1)->where('college_type', 'deanship')->get(),
			'centers' => College::select('name', 'name_en', 'slug')->where('active', 1)->where('college_type', 'center')->get()

		];
	}
	public function archive(Request $request)
	{
		$data = $this->getSharedData();

		$data['ads'] = Ad::select([
			'ads.id',
			'ads.title',
			'ads.slug',
			'ads.detail_portion',
		])
			->withFirstImage()

			->where('lang', 1)
			->where('active', 1)
			->orderByRaw('priority desc')
			->paginate(8);

		if ($data['ads']->isEmpty()) {
			abort(404);
		}
		$data['recent_ads'] = Ad::select('title')
			->where('lang', 1)
			->orderByRaw('priority desc')
			->limit(3)
			->get();

		return view('ar/ads_archive', ['data' => $data]);
	}
	public function details2(Request $request, $slug)
	{
		$ad = Ad::select('title', 'ad_date', 'detail', 'photos', 'file')
			->where([
				['lang', 1],
				['slug', '=', $slug]
			])
			->firstOrFail();

		$data = $this->getSharedData();

		$photoIds = array_filter(explode(',', $ad->photos));
		$list_photos = AlbumPhoto::select('id', 'img as photo', 'title')
			->whereIn('id', $photoIds)
			->get();

		$ad->photos = $list_photos;
		$data['ads'] = $ad;

		$data['recent_ads'] = Ad::select('title')
			->where('lang', 1)
			->orderByRaw('priority desc')
			->limit(3)
			->get();

		return view('ar/ads_details', ['data' => $data]);
	}

	public function details(Request $request, $slug)
	{
		$data = $this->getSharedData();

		$data['ads'] = Ad::select('id', 'title', 'ad_date', 'detail', 'file')
			->with([
				'photos' => function ($query) {
					$query->select('album_photos.id', 'title', 'img');
				}
			])
			->where('slug', $slug)
			->firstorfail();

		return view('ar/ads_details', ['data' => $data]);
	}
}