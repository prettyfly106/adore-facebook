<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
          'page_id' => 'required',
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
}
