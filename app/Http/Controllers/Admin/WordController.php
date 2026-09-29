<?php

namespace App\Http\Controllers\Admin;

use Storage;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WordController extends Controller
{
	public function index(Request $request)
	{
		$data = \DB::table('pages')->where('page_title', 'dean_word')->get();
		return view('admin.word')->with('data', $data);
	}

	public function store(Request $request)
	{
		$data = \DB::table('pages')->select('page_id','page_lang')->where('page_title', 'dean_word')->where('page_lang', $request->lang)->get();
		if(count((array)$data))
		{

			$img_path = $file_path = '';
			if($request->hasFile('img')) {
				if($request->file('img')->isValid()) {
					try {
						$file = $request->file('img');
						$filename = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
						$img_path = $file->storeAs('images', $filename);
					} catch (Exception $e) {
                return response()->json(['message' => 'Error processing file: ' . $e->getMessage(), 'status' => 500], 500);
            }
				}
			}

			if($request->hasFile('file')) {
				if($request->file('file')->isValid()) {
					try {
						$file = $request->file('file');
						$filename = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
						$file_path = $file->storeAs('files', $filename,'public');
					} catch (Exception $e) {
                return response()->json(['message' => 'Error processing file: ' . $e->getMessage(), 'status' => 500], 500);
            }
				}
			}

			\DB::table('pages')->insert(array('page_title'  => 'dean_word','page_lang' =>  $request->lang,
					'page_det_portion' =>   $request->portion,'page_img' =>   $img_path,
					'page_file' =>   $file_path,'page_det' => htmlspecialchars($request->det_code)));
			return "success";
		}
		else
		return "error";
	}

	public function show(Request $request,$id)
	{
		$edit_data = \DB::table('pages')->where('page_id', $id)->first();
		if($edit_data != '')
		{
			$data = \DB::table('pages')->select('page_id','page_lang')->where('page_title', 'dean_word')->get();
			return view('admin.word')->with('data', $data)->with('edit_data',$edit_data);
		}
		else
		return redirect()->route('word_index');
	}

	public function update(Request $request)
	{
		$arr = array('page_det_portion' => $request->portion,'page_det' => htmlspecialchars($request->det_code));
		$docs = \DB::table('pages')->select('page_img','page_file')->where('page_id', $request->id)->get();
		if($request->hasFile('img')){
			if($request->file('img')->isValid()) {
				try {
					$file = $request->file('img');
					$filename = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
					$img_path = $file->storeAs('images', $filename);
					if($docs[0]->page_img != ''){ Storage::delete($docs[0]->page_img); }
					$arr['page_img'] = $img_path;
				} catch (Exception $e) {
                return response()->json(['message' => 'Error processing file: ' . $e->getMessage(), 'status' => 500], 500);
            }
			}
		}
		if($request->hasFile('file')){
			if($request->file('file')->isValid()) {
				try {
					$file = $request->file('file');
					$filename = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
					$img_path = $file->storeAs('files', $filename);
					if($docs[0]->page_file != ''){ Storage::delete($docs[0]->page_file); }
					$arr['page_file'] = $img_path;
				} catch (Exception $e) {
                return response()->json(['message' => 'Error processing file: ' . $e->getMessage(), 'status' => 500], 500);
            }
			}
		}

		\DB::table('pages')->where('page_id', $request->id)->update($arr);
		return "success";
	}

	public function destroy(Request $request,$id)
	{
		$docs = \DB::table('pages')->select('page_img','page_file')->where('page_id', $request->id)->get();
		if(count($docs) != 0)
		{
			if($docs[0]->page_img != ''){ Storage::delete($docs[0]->page_img); }
			if($docs[0]->page_file != ''){ Storage::delete($docs[0]->page_file); }
			\DB::table('pages')->where('page_id', $request->id)->delete();
		}
		return redirect()->route('word_index');
	}
}
