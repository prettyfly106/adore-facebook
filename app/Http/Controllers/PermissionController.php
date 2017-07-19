<?php

namespace App\Http\Controllers;

use App\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Permission::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        // return view('permission.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'permissionName' => 'required|max:45',
            'permissionDescription' => 'required|max:100',
        ]);

        $permission = Permission::create($request->all());
        return $permission;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        return Permission::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $permission = Permission::find($id);
        return $permission;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $permission = Permission::find($id);

        if (is_null($permission)) {
          return response()->json([
                'status' => 'FAILED',
                'error' => 'ID NOT EXIST'
            ], 404);
        }
        $input = $request->all();
        $permission->update($input);
        //$permission->save();
        $permission = Permission::query();
        $permission->where('idPermission', $id);
        return response()->json([
          'status' => 'SUCCESS',
          'data' => $permission->get()
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $permission = Permission::find($id);
        if ($permission != null) {
            $permission->delete();
            return response()->json([
                'status' => 'SUCCESS'
            ], 200);
        }
        else {
            return response()->json([
                'status' => 'FAILED',
                'error' => 'ID NOT EXIST'
            ], 404);
        }
    }
}
