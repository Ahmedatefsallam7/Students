<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Joiner;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\SubjectResource;

class JoinerController extends Controller
{
    function store(Request $request)
    {
        $user = User::find(auth()->id());
        $joiner = Joiner::where('email', $user->email)->first();
        $subjects = Subject::where('sub_code', $request->sub_code)->first();

        if ($subjects) {
            if (!$joiner) {
                $new =  Joiner::create([
                    'name' => auth()->user()->name,
                    'email' => auth()->user()->email,
                    'password' => auth()->user()->password,
                ]);

                DB::table('subject_joiners')->insert([
                    'joiner_id' => $new->id,
                    'subject_id' => $subjects->id,
                ]);
                return response()->json(['msg' => ucwords("You are joined now to this subject")]);
            }
        } else {
            return response()->json(['msg' => ucwords("this subject code is invalid")]);
        }

        $sub_joiner = DB::table('subject_joiners')
            ->where('joiner_id', $joiner->id ?? 1)
            ->where('subject_id', $subjects->id ?? 1)
            ->first();

        if ($subjects) {
            if (!($sub_joiner)) {
                DB::table('subject_joiners')->insert([
                    'joiner_id' => $joiner->id,
                    'subject_id' => $subjects->id,
                ]);
                return response()
                    ->json(['msg' => ucwords("You are joined now to this subject $subjects->sub_name")]);
            } else {
                return response()
                    ->json(['msg' => ucwords("you are joined to this subject before")]);
            }
        } else {
            return response()
                ->json(['msg' => ucwords("subject code not valid")]);
        }
    }
    function getSubjectsJoinedByUser($id)
    {
        $joiner = Joiner::find($id);
        if ($joiner) {
            return
                SubjectResource::collection($joiner->subjects);
        } else {
            return response()
                ->json(['msg' => ucwords("this user not joined to any subjects")]);
        }
    }
    function getUsersJoinedTOSubject($id)
    {
        $subject = Subject::where('id', $id)->first();
        return ($subject) ?
            UserResource::collection($subject->joiners) : response()
            ->json(['msg' => ucwords("this subject not joined by any users")]);
    }
}
