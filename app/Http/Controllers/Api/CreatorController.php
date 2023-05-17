<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Creator;
use App\Models\Subject;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\SubjectResource;
use Illuminate\Support\Facades\Validator;

class CreatorController extends Controller
{
    function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sub_name' => 'required|string|min:2|max:10|unique:subjects',
            'sub_code' => 'unique:subjects',
        ]);

        if ($validator->fails()) {
            return  $validator->errors();
        }

        $creator = Creator::where('email', auth()->user()->email)->first();
        if (!$creator) {
            $new = Creator::create([
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'password' => auth()->user()->password,
            ]);

            $subject = Subject::create([
                'sub_name' => $request->sub_name,
                'sub_code' => Str::random(6),
                'creator_id' => $new->id,
            ]);
            return new SubjectResource($subject);
        }
        $subject = Subject::create([
            'sub_name' => $request->sub_name,
            'sub_code' => Str::random(6),
            'creator_id' => $creator->id,
        ]);
        return new SubjectResource($subject);
    }

    function getSubjectsCreatedByUser($id)
    {
        $creator = Creator::find($id);
        if ($creator) {
            $subs = Subject::where('creator_id', $creator->id)->get();
            return SubjectResource::collection($subs);
        } else {
            return response()
                ->json(['msg' => ucwords("This User dose't Created Subjects")]);
        }
    }
    function getUserCreatedSubject($id)
    {
        $sub = Subject::find($id);
        return ($sub) ?
            new UserResource($sub->creator)
            : response()->json(['msg' => ucwords("this subject dose't exists")]);
    }
}
