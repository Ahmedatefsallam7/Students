<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{

    function index()
    {
        $users = User::latest()->get();
        return (count($users) > 0) ?
            UserResource::collection($users) : abort(404);
    }

    function getUser($id)
    {
        $user = User::find($id);
        return ($user) ?
            new UserResource($user) : "This user with id $id not exits";
    }
    function create()
    {
        return view('users.addUser');
    }

    function  store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:50',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return  $validator->errors();
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        return to_route('users.index');
    }

    function edit($id)
    {
        $user = User::find($id);
        if ($user) {
            if (auth()->user()->id == $user->id) {
                return view('users.editUser', compact('user'));
            } else {
                abort(401);
            }
        }
        return "User with id $id not found ";
    }

    function  update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:3,50',
            'email' => 'required|string|email|max:100',
            // 'password' => 'min:6',

        ]);

        if ($validator->fails()) {
            return  $validator->errors();
        }

        $user = User::find($request->id);
        if ($user) {
            if (auth()->user()->id == $user->id) {
                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    // 'password' => Hash::make($request->password),
                ]);
                return response()->json($user);
            } else {
                abort(401);
            }
        }
        abort(404);;
    }

    function destroy($id)
    {
        $user = User::find($id);
        if ($user) {
            if (auth()->user()->id == $user->id) {
                $user->delete();
                return 'User Deleted Successfully ';
            }
            abort(401);
        }
        abort(404);
    }

    function restoreUser($id)
    {
        $user = User::onlyTrashed()->where('id', $id)->restore();
        return ($user) ?
            'User Restored Successfully'
            : "User with id $id Not Found to Restore";
    }
    
    function restoreAll()
    {
        $users = User::onlyTrashed()->restore();
        return ($users) ?
            'All Users Restored Successfully'
            : 'There\'s No Deleted Users To Restore';
    }
}
