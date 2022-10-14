<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use App\Models\College;
use App\Models\Category;
use App\Models\Department;
use App\Models\CategoryPage;
use Illuminate\Http\Request;
use App\Models\CategoryPageUser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CategoryPageUserController extends Controller
{
    public function index()
    {
        $users = \DB::table('category_page_user')
                ->join('users', 'category_page_user.user_id', '=', 'users.id')
                ->distinct()
                ->select('name')
                ->get()->toArray();
        $main_array = array();
        foreach ($users as $user)
        {
            $data = \DB::table('categories')
                ->join('category_pages', 'categories.id', '=', 'category_pages.category_id')
                ->join('category_page_user', 'category_pages.id', '=', 'category_page_user.category_page_id')
                ->join('users', 'users.id', '=', 'category_page_user.user_id')
                ->select('categories.title as category_title','category_pages.title as page', 'url')
                ->where('users.name',$user->name)
                ->get();
            $main_array[$user->name] = $data;
        }

        //صلاة الجمعة 
        // dd($main_array);
        // $data = \DB::table('categories')
        //         ->join('category_pages', 'categories.id', '=', 'category_pages.category_id')
        //         ->join('category_page_user', 'category_pages.id', '=', 'category_page_user.category_page_id')
        //         ->join('users', 'users.id', '=', 'category_page_user.user_id')
        //         ->select('categories.title as category_title','users.name as user_name','category_pages.title as page', 'url')
        //         ->get();        
        // $count_categories = array_count_values($data->pluck('category_title')->toArray());
        // $count_users = $data->pluck('user_name')->unique()->toArray();
        // foreach($count_users as $user)
        // {
        //     echo $user;
        // }
        $arr2 = $arr3 = array();

        foreach($main_array as $key => $value)
        {
            $arr = $arr2 = array();
            $count_categories = array_count_values($value->pluck('category_title')->toArray());
            foreach($value as $item)
            {
                
                $arr[$item->url] = $item->page;
                if($count_categories[$item->category_title] > 1)
                {
                    foreach($value as $record)
                    {
                        if($item->category_title == $record->category_title && in_array($record->page,$arr) == false){
                            $arr[$record->url] = $record->page;
                        }
                    }

                }
                $arr2[] = array($item->category_title,$arr);
                $arr =array();
            }
            $arr3[] = array($key,$arr2);
        }
        $data = array();
        foreach($arr3 as $item) 
        {
            $sort = array();
            foreach($item[1] as $key)
            {
                if(array_key_exists($key[0], $sort)) continue;
                $sort[$key[0]] = $key[1];
            }
            $data[$item[0]] = $sort;
            $sort = '';
        }
        
        $departments = Department::with('college')->get();
        $colleges = College::select('id','name')->get();
        $categories = Category::select('id','title')->get();
        return view('admin.category_page_users')->with('data', $data)->with('departments',$departments)->with('colleges',$colleges)->with('categories',$categories);
    }

    public function store(Request $request)
    {
        $data = CategoryPageUser::select('category_page_id')
            ->whereColumn([['category_page_id', '=', DB::raw('"' . $request->category_page_id . '"')], ['user_id', '=', DB::raw('"' . $request->user_id . '"')]])->get();
        if ($data->count() == 0) {
            $validator = Validator::make($request->all(), [
                'category_page_id' => 'required',
                'user_id' => 'required',
            ]);
            CategoryPageUser::create(array_merge($validator->validated(),
                ['auth_id' => Auth::user()->id],
            ));
            return "success";
        } else {
            return "error";
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CategoryPageUser  $categoryPageUser
     * @return \Illuminate\Http\Response
     */
    public function show(CategoryPageUser $categoryPageUser)
    {
        dd($categoryPageUser);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CategoryPageUser  $categoryPageUser
     * @return \Illuminate\Http\Response
     */
    public function edit(CategoryPageUser $categoryPageUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CategoryPageUser  $categoryPageUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CategoryPageUser $categoryPageUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CategoryPageUser  $categoryPageUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(CategoryPageUser $categoryPageUser)
    {
        //
    }
}
