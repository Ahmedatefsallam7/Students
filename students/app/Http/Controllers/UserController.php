<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    function index()
    {
        $users = User::all();

        return $users ?? 'Not Found';
    }

    function getUser($id)
    {

        $user = User::find($id);

        return  $user ?? 'Not Found  ';
    }
}
