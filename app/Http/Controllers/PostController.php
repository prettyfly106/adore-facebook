<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->beforeFilter('auth', ['on' => 'post']);
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function insertPost(Request $request)
    {
        if (!empty($request->code)) {
          $checkPost = Post::where('code', $request->post_id)->first();
          if ($checkPost) {
            return response()->json([
              'status' => 'SUCCESS',
              'Post' => $checkPost
            ], 201);
          }
        }

        $validator = Validator::make($request->all(), [
          'page_id' => 'required',
          'post_id' => 'required|max:50|unique:Posts'
        ], $this->validateMessages());

        if ($validator->fails()) {
          return response()->json([
            'status' => 'FAILED',
            'errors' => $validator->errors()
          ], 422);
        }

        $page = Page::find($request->page_id);
        if (!$page) {
          return response()->json([
            'status' => 'FAILED',
            'error' => 'Unknown page'
          ], 422);
        }

        $Post = new Post;
        $Post->page_id()->associate($page);
        $Post->post_id = $request->post_id;
        $Post->creator_id = $request->creator_id;
        $Post->create_date = $request->create_date;
        $Post->product_id = $request->product_id;       
        $Post->save();

        return response()->json([
          'status' => 'SUCCESS',
          'Post' => $Post
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
