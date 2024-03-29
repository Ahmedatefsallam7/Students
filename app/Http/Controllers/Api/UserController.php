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
            return $this->apiResponse(UserResource::collection($users),  'Get All Users');
        } else {
            return $this->apiResponse('not found', 'Users Not Found');
        }
    }

    function getUser($id)
    {
        $user = User::find($id);

        if ($user) {
            return $this->apiResponse(new UserResource($user),  'Ok');
        } else {
            return $this->apiResponse('not found ', 'User Not Found');
        }
    }

    function  store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse('null', $validator->errors());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        if ($user) {
            return $this->apiResponse(new UserResource($user), 'User Info');
        } else {

            return $this->apiResponse(null, 'Please Create User Again');
        }
    }

    function  update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse('null', $validator->errors());
        }

        $user = User::find($request->id);

        if (isset($user)) {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            return $this->apiResponse(new UserResource($user),  'User Info Updated Successfully');
        } else {
            return response()->json(['msg' => 'User Not Found To Update !!']);
        }
    }

    function destroy($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return response()->json(['msg' => 'User Info Deleted Successfully']);
        } else {
            return response()->json(['msg' => 'User Not Found']);
        }
    }

    function restoreUser($id)
    {
        $user = User::onlyTrashed()->where('id', $id)->restore();
        return ($user) ?
            $this->apiResponse(new UserResource(User::where('id', $id)->first()), 'User Restored Successfully')
            : response()->json(['msg' => 'User Not Found to Restore']);
    }

    function restoreAll()
    {
        $users = User::onlyTrashed()->restore();
        return ($users) ?
            $this->apiResponse(UserResource::collection(User::get()), 'All User Restored Successfully')
            : response()->json(['msg' => 'There\'s No Deleted Users To Restore']);
    }
}
