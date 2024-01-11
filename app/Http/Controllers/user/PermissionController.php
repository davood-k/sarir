<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Permission;
use App\User;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function create(User $user){
        return view('admin.users.permissions' , compact('user'));
    }

    
    public function store(Request $request , User $user){
        
        
        $user->permissions()->sync($request->permissions);
        $user->roles()->sync($request->roles);


        return redirect(route('users.index'));
    }
}
