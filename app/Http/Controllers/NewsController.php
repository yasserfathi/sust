<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\College;
use App\Models\AlbumPhoto;
use App\Models\News;
use Illuminate\Http\Request;


class NewsController extends Controller
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
			'recent_news' => News::select('id', 'title', 'slug', 'news_date')
				->where('active', 1)
				->where('lang', 2)
				->orderBy('priority', 'desc')
				->orderByDesc('id')
				->limit(3)
				->get()
		];
	}
	public function archive(Request $request)
	{
		$data = $this->getSharedData();

		$data['news'] = News::select([
			'id',
			'title',
			'detail_portion',
			'news_date',
			'slug'
		])
			->withFirstImage()
			->where('lang', 2)
			->where('active', 1)
			->orderByDesc('id')
			->paginate(9)->onEachSide(1);

		if ($data['news']->isEmpty()) {
			// abort(404);
		}

		return view('news_archive', ['data' => $data]);
	}
	public function details(Request $request, $slug)
	{
		$data = $this->getSharedData();

		$data['news'] = News::select('id', 'title', 'news_date', 'detail', 'file')
			->with(['photos'])
			->where([
				['lang', 2],
				['slug', '=', $slug]
			])
			->firstorfail();

		return view('news_details', ['data' => $data]);
	}
}