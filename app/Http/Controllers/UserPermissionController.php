<?php

namespace App\Http\Controllers;

use App\UserPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserPermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return UserPermission::all();
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
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'idUser' => 'required|max:10',
            'idPermission' => 'required|max:10',
        ]);
        $input = $request->all();
        $user_permission = UserPermission::create($input);
        //return $user_permission;
        return response()->json([
              'status' => 'SUCCESS',
              'data' => $user_permission
          ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\UserPermission  $userPermission
     * @return \Illuminate\Http\Response
     */

    public function getPermission($userId)
    {
        //
        $permission = DB::table('permission')
            ->join('user_has_permission', 'permission.idPermission', '=', 'user_has_permission.idPermission')
            ->select('permission.idPermission', 'permission.permissionName', 'permission.permissionDescription')
            ->where('user_has_permission.idUser', $userId)
            ->get();
        return $permission;
    }

    public function getGrantedUser($permisionId)
    {

      $user = DB::table('users')
          ->join('user_has_permission', 'users.id', '=', 'user_has_permission.idUser')
          ->select('users.username', 'users.name', 'users.email')
          ->where('user_has_permission.idPermission', $permisionId)
          ->get();
      return $user;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\UserPermission  $userPermission
     * @return \Illuminate\Http\Response
     */
    public function edit(UserPermission $userPermission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserPermission  $userPermission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $permissionId)
    {
        $this->validate($request, [
            'idUser' => 'required|max:10',
            'idPermission' => 'required|max:10',
        ]);

        UserPermission::find($permissionId)->update($request->all());
        return response()->json([
          'status' => 'SUCCESS',
          'userPermission' => Permission::find($permissionId),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserPermission  $userPermission
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $user_permission = UserPermission::find($id);
        if ($user_permission != null) {
            $user_permission->delete();
            return response()->json([
                'status' => 'SUCCESS'
            ], 200);
        }
        else {
            return response()->json([
                'status' => 'ID NOT EXIST'
            ], 200);
        }
    }
}
