<?php

namespace App\Http\Controllers\user;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        // $this->authorize('show-users');
        $users = User::latest()->paginate(25);
        return view('admin.users.all', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

        /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data= $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        User::create($data);

        return redirect(route('users.index'));
    }

       /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
    
        return view('admin.users.edit' , compact('user'));
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back();
    }
}