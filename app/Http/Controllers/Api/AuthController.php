<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Mail\UserMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ApiResponseTrait;
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);
        if ($validator->fails()) {
            return $this->apiResponse('null', $validator->errors());
        }
        // return $this->apiResponse(new UserResource(auth()->user()), 'You are logged');

        // check token
        
        if (!$token = auth()->attempt($validator->validated())) {
            return $this->apiResponse('null','Unauthorized');
            // return response()->json(['error' => 'Unauthorized'], 401);
        }
        // Mail::to('ahmedatefsallam7@gmail.com')->send(new UserMail(auth()->user()->name));
        return $this->createNewToken($token);
        
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:8',
            // 'count_days' => 'required|integer|max:365|min:0'
        ]);
        if ($validator->fails()) {
            return $this->apiResponse('null',  $validator->errors());
        }
        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        // check token
        if (!$token = auth()->attempt($validator->validated())) {
            return $this->apiResponse('null', 'Unauthorized');
            // return response()->json(['error' => 'Unauthorized'], 401);
        }
        // Mail::to($request->email)->send(new UserMail($request->name));
        return $this->createNewToken($token);

        // return $this->apiResponse(new UserResource($user),'User successfully registered');
        // return response()->json([
        //     'message' => 'User successfully registered',
        //     'user' => $user
        // ], 201);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(Auth::refresh());
    }

    public function userProfile()
    {
        return $this->apiResponse(new UserResource(auth()->user()), "User Info");
    }
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
            'user' => new UserResource(auth()->user())
        ]);
    }
}