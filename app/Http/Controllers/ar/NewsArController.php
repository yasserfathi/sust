<?php

namespace App\Http\Controllers\ar;

use App\Http\Controllers\Controller;
use App\Models\College;
use App\Models\News;
use Illuminate\Http\Request;


class NewsArController extends Controller
{
	private function getSharedData()
	{
		return [
			'colleges' => College::select('name', 'name_en', 'slug')->where('active', 1)->where('college_type', 'college')->get(),
			'deanships' => College::select('name', 'name_en', 'slug')->where('active', 1)->where('college_type', 'deanship')->get(),
			'centers' => College::select('name', 'name_en', 'slug')->where('active', 1)->where('college_type', 'center')->get(),
			'recent_news' => News::select('id', 'title', 'slug')
				->where('active', 1)
				->where('lang', 1)
				->orderBy('priority', 'desc')
				->orderBy('id', 'desc')
				->limit(3)
				->get()
		];
	}
	public function archive(Request $request)
	{
		$data = $this->getSharedData();

		$data['news'] = News::select([
			'id', // Always select ID, relationships/joins usually need it
			'title',
			'detail_portion',
			'news_date',
			'slug'
		])
			->withFirstImage() // Call Scope AFTER select to append the subquery
			->where('lang', 1)
			->where('active', 1)
			->orderBy('id', 'desc') // Use simple 'id' unless joining manually
			->paginate(8);

		if ($data['news']->isEmpty()) {
			abort(404);
		}

		return view('ar/news_archive', ['data' => $data]);
	}
	public function details(Request $request, $slug)
	{
		$data = $this->getSharedData();

		$data['news'] = News::select(	'id', 'title', 'news_date', 'detail', 'file')
			->with([
				'photos' => function ($query) {
					$query->select('album_photos.id', 'title', 'img');
				}
			])
			->where([
				['slug', '=', $slug]
			])
			->firstorfail();

		return view('ar/news_details', ['data' => $data]);
	}
}