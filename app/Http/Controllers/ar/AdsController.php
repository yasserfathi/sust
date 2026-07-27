<?php

namespace App\Http\Controllers\ar;

use App\Http\Controllers\Controller;
use App\Models\College;
use App\Models\AlbumPhoto;
use App\Models\News;
use Illuminate\Http\Request;


class AdsController extends Controller
{
    public function archive(Request $request)
	{
		$data = [];
        $data['colleges'] = College::select('name')->where('active',1)->where('college_type', 'college')->get();
        $data['deanships'] = College::select('name')->where('active',1)->where('college_type', 'deanship')->get();
        $data['centers'] = College::select('name')->where('active',1)->where('college_type', 'center')->get();

		$data['news'] =  News::withFirstImage()
		->select([
			'news.title',          // Explicitly specify table
			'news.detail_portion', // Explicitly specify table
			'first_photos.thumb_img as first_image'  // From our join
		])
		->whereNotNull('photos')
		->where('photos', '!=', '')
		->where('lang', 1)
		->where('active', 1)
		->orderBy('news.id', 'desc')
		->paginate(8);

		if($data['news']->count() != 0)
		{
			return view('news_archive',compact("data"));
		}
		else abort(404);
	}
	public function details(Request $request,$title)
	{
        $title  = str_replace('-', ' ', $title);
        $data = [];
        $data['colleges'] = College::select('name')->where('active',1)->where('college_type', 'college')->get();
        $data['deanships'] = College::select('name')->where('active',1)->where('college_type', 'deanship')->get();
        $data['centers'] = College::select('name')->where('active',1)->where('college_type', 'center')->get();
        $data['news'] = News::select('title','news_date','detail','photos','file')->where([['lang',1],['title','=',$title]])->first();
		$list_photos = AlbumPhoto::select('id', 'img as photo', 'title')->whereIn('id', explode(',', $data['news']->photos))->get();
        $data['news']->photos = $list_photos;
		if($data['news']->count() != 0)
		{
			$data['recent_news'] = News::select( 'title')
			->where('lang', 1)
			->where('priority', 1)
			->limit(3)
			->get();
			return view('news_details',compact("data"));
		}
		else abort(404);
	}

}