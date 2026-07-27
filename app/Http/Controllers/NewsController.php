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
		return [
			'colleges' => College::select('name_en', 'slug')->where('active', 1)->where('college_type', 'college')->get(),
			'deanships' => College::select('name_en', 'slug')->where('active', 1)->where('college_type', 'deanship')->get(),
			'centers' => College::select('name_en', 'slug')->where('active', 1)->where('college_type', 'center')->get(),
			'recent_news' => News::select('id', 'title', 'slug')
				->where('active', 1)
				->where('lang', 2)
				->orderBy('priority', 'desc')
				->orderBy('id', 'desc')
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
			->where('lang', 2)
			->where('active', 1)
			->orderByRaw('priority desc') // Use simple 'id' unless joining manually
			->paginate(8);

		if ($data['news']->isEmpty()) {
			abort(404);
		}

		return view('news_archive', ['data' => $data]);
	}
	public function details(Request $request, $slug)
	{
		$data = $this->getSharedData();

		$data['news'] = News::select('id', 'title', 'news_date', 'detail', 'file')
			->with([
				'photos' => function ($query) {
					$query->select('album_photos.id', 'title', 'img');
				}
			])
			->where([
				['lang', 2],
				['slug', '=', $slug]
			])
			->firstorfail();

		return view('news_details', ['data' => $data]);
	}
}