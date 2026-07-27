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
		return [
			'colleges' => College::select('name_en', 'slug')->where('active', 1)->where('college_type', 'college')->get(),
			'deanships' => College::select('name_en', 'slug')->where('active', 1)->where('college_type', 'deanship')->get(),
			'centers' => College::select('name_en', 'slug')->where('active', 1)->where('college_type', 'center')->get()

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
		])
			->withFirstImage()
			->where('lang', 2)
			->where('active', 1)
			->orderByRaw('priority desc')
			->orderBy('id', 'desc')
			->paginate(8);

		if ($data['ads']->isEmpty()) {
			abort(404);
		}
		$data['recent_ads'] = Ad::select('title')
			->where('lang', 2)
			->orderByRaw('priority desc')
			->limit(3)
			->get();

		return view('ads_archive', ['data' => $data]);
	}
	public function details(Request $request, $slug)
	{
		$ad = Ad::select('id', 'title', 'ad_date', 'detail', 'file')
			->with('photos')
			->where([
				['lang', 2],
				['slug', '=', $slug]
			])
			->firstOrFail();

		$data = $this->getSharedData();
		$data['ads'] = $ad;

		$data['recent_ads'] = Ad::select('title')
			->where('lang', 2)
			->orderByRaw('priority desc')
			->limit(3)
			->get();

		return view('ads_details', ['data' => $data]);
	}
}