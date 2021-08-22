<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user',[
            'users' => User::get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|max:100|unique:users',
            'name' => 'required|max:255',
            'password' => 'required',
            'role' => 'required',
        ]);
        //hasing
        $request['password'] = bcrypt($request->password);

        User::create($request->except('_token'));

        return redirect()->route('user.index')->with('message', 'Success creating user!');
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
        $request->validate([
            'username' => 'required|max:100|unique:users,username,'.$id,
            'name' => 'required|max:255',
            'role' => 'required',
        ]);

        if($request->password)
            $request['password'] = bcrypt($request->password);
        else
            unset($request['password']);

        User::find($id)->update($request->except('_token'));

        return redirect()->route('user.index')->with('success', 'Successfull update user!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            User::findOrFail($id)->delete();

            return redirect()->route('user.index')->with('success', 'Successfull deleting user!');
       } catch (\Throwable $th) {
            return redirect()->route('user.index')->with('fail', 'Failed deleting user!');
       }
    }
}
