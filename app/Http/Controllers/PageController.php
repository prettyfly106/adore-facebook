<?php

namespace App\Http\Controllers;

use App\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use JWTAuth;

class PageController extends Controller
{
    public function insertPage(Request $request)
    {
        if (!empty($request->code)) {
          $checkPage = Page::where('code', $request->Page_id)->first();
          if ($checkPage) {
            return response()->json([
              'status' => 'SUCCESS',
              'Page' => $checkPage
            ], 201);
          }
        }

        $validator = Validator::make($request->all(), [
          'user_id' => 'required',
          'Page_id' => 'required|max:50|unique:Pages'
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

        $Page = new Page;
        $Page->page_id()->associate($page);
        $Page->Page_id = $request->page_id;
        $Page->creator_id = $request->creator_id;
        $Page->create_date = $request->create_date;
        $Page->product_id = $request->product_id;
        $Page->save();

        return response()->json([
          'status' => 'SUCCESS',
          'Page' => $Page
        ], 201);
    }

    public function getPage()
    {
        //
        $user = JWTAuth::parseToken()->authenticate();
        $page = DB::table('facebook_pages')
            ->select('facebook_pages.*')
            ->where('user_id', $user->id)
            ->get();

        return response()->json([
          'status' => 'SUCCESS',
          'data' => $page
        ], 200);
    }

    public function getFollowingPage() {
      $user = JWTAuth::parseToken()->authenticate();
      $page = DB::table('facebook_pages')
          ->select('facebook_pages.page_id')
          ->where('user_id', $user->id)
          ->where('status', 1)
          ->get();
      return response()->json([
        'status' => 'SUCCESS',
        'data' => $page
      ], 200);
    }

    public function followPage(Request $request, $pageId) {
      $user = JWTAuth::parseToken()->authenticate();
      $page = DB::table('facebook_pages')
          ->select('facebook_pages.*')
          ->where('user_id', $user->id)
          ->where('page_id', $pageId);
      if ($page->get()->isEmpty()) {
        $page = new Page;
        $page->page_id =$pageId;
        $page->user_id =$user->id;
        //$page->approve_date =  Carbon\Carbon::now();
        $page->status = 1;
        $page->save();
      }
      else {
        $page->update(array('status' => 1));
        $page = $page->get();
      }
      return response()->json([
        'status' => 'SUCCESS',
        'data' => $page
      ], 200);
    }

}
