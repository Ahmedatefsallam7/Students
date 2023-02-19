<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use ApiResponseTrait;
    function index()
    {
        $users = User::all();

        if ($users) {
            return $this->apiResponse(UserResource::collection($users), 200, 'Ok');
        } else {
            return $this->apiResponse('not found', 404, 'Not Found');
        }
    }

    function getUser($id)
    {
        $user = User::find($id);

        if ($user) {
            return $this->apiResponse(new UserResource($user), 200, 'Ok');
        } else {
            return $this->apiResponse('not found ', 404, 'Not Found');
        }
    }

    function  store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
            'count_days' => 'required|integer|max:365|min:0'
        ]);


        if ($validator->fails()) {
            return $this->apiResponse('null', 400, $validator->errors());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'count_days' => $request->count_days,
        ]);
        if ($user) {
            return $this->apiResponse(new UserResource($user), 201, 'Ok');
        } else {

            return $this->apiResponse(null, 404, 'Error');
        }
    }

    function  update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
            'count_days' => 'required|integer|max:365|min:0'
        ]);


        if ($validator->fails()) {
            return $this->apiResponse('null', 400, $validator->errors());
        }

        $user = User::find($request->id);

        if (isset($user)) {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'count_days' => $request->count_days,
            ]);
            return $this->apiResponse(new UserResource($user), 201, 'Ok');
        } else {
            return $this->apiResponse('null', 404, 'User Not Found');
        }
    }

    function destroy($id)
    {

        $user = User::find($id);

        if ($user) {
            $user->delete();
            return $this->apiResponse('null', 404, 'User Deleted Successfully');
        } else {
            return $this->apiResponse('null', 404, 'User Not Found');
        }
    }

    function restoreUser($id)
    {
        $user = User::onlyTrashed()->where('id', $id)->restore();
        return ($user) ?
            $this->apiResponse(new UserResource(User::where('id', $id)->first()), 200, 'User Restored Successfully')
            : $this->apiResponse('null', 404, 'User Not Found to Restore');
    }

    function restoreAll()
    {
        $users = User::onlyTrashed()->restore();
        return ($users) ?
            $this->apiResponse(UserResource::collection(User::get()), 200, 'All User Restored Successfully')
            : $this->apiResponse('null', 200, 'There\'s no users to restore');
    }
}
