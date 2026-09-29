<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\College;
use App\Models\AlbumPhoto;
use App\Models\Workshop;
use App\Models\News;
use Illuminate\Http\Request;


class EventsController extends Controller
{
	private function getSharedData($type)
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
				->get(),
			'recent_events' => Workshop::select('id', 'title', 'slug', 'workshop_date')
				->where('type', rtrim($type, 's'))
				->where('active', 1)
				->where('lang', 2)
				->orderByDesc('id')
				->limit(4)
				->get()
		];
	}
	public function archive(Request $request)
	{
		$type = $request->route('type', 'workshops');
		$data = $this->getSharedData($type);
		$data['type'] = rtrim($type, 's');

		$data['events'] = Workshop::select([
			'id',
			'title',
			'detail_portion',
			'workshop_date',
			'slug'
		])
			->withFirstImage()
			->where('type', rtrim($type, 's'))
			->where('active', 1)
			->where('lang', 2)
			->orderByDesc('id')
			->paginate(9)->onEachSide(1);



		return view('events_archive', ['data' => $data]);
	}

	public function details(Request $request, $slug)
	{
		$type = $request->route('type', 'workshop');
		$data = $this->getSharedData($type);
		$data['type'] = $type;

		$data['event'] = Workshop::select('id', 'title', 'workshop_date', 'detail', 'file')
			->with(['photos:id,thumb_img as thumb_photo,img as photo,title'])
			->where([
				['lang', 2],
				['type', '=', rtrim($type, 's')],
				['slug', '=', $slug]
			])
			->firstOrFail();

		return view('events_details', ['data' => $data]);
	}
}