<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // TODO:
        return User::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        // return view('user.create');
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
            'username' => 'required|max:45|unique:users,username',
            'password' => 'required|min:6|max:45',
            'image' => 'required|max:100',
            'name' => 'required|max:10',
            'phone' => 'required|numeric',
            'client_id' => 'required|integer',
            'dateOfBith' => 'required|date',
            'email' => 'required|email',
            'address' => 'required|max:250'
        ]);
        
        $user = new User;
        $user->fill($request->all());
        $user->dateOfBith = date('Y-m-d H:i:s', strtotime($request->input('dateOfBith')));
        $user->password = bcrypt($request->input('password'));
        $user->save();
        return $user;
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
        return User::find($id);
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
        return User::find($id);
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
        $this->validate($request, [
            'username' => 'required|max:45|unique:users,username',
            'password' => 'required|min:6|max:45',
            'image' => 'required|max:100',
            'name' => 'required|max:10',
            'phone' => 'required|numeric',
            'client_id' => 'required|integer',
            'dateOfBith' => 'required|date',
            'email' => 'required|email',
            'address' => 'required|max:250'
        ]);
        
        $user = User::find($id);
        $user->fill($request->all());
        $user->dateOfBith = date('Y-m-d H:i:s', strtotime($request->input('dateOfBith')));
        $user->password = bcrypt($request->input('password'));
        $user->save();
        return $user;
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
        $user = User::find($id);
        return $user->delete();
    }
}
