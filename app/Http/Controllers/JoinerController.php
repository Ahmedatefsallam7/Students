<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Joiner;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UserResource;
use App\Http\Resources\SubjectResource;

class JoinerController extends Controller
{
    function joinSubject($id)
    {
        $sub = Subject::findOrFail($id);
        return view('joiner.joinSubject', compact('sub'));
    }

    function store(Request $request)
    {
        $user = User::find(auth()->id());
        $joiner = Joiner::where('email', $user->email)->first();
        $subjects = Subject::where('sub_code', $request->subject_code)->first();

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
                return response(ucwords("You are joined now to this subject"));
            }
        } else {
            return response(ucwords("this subject code is invalid"));
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
                return response(ucwords("You are joined now to this subject $subjects->sub_name"));
            } else {
                return response(ucwords("you are joined to this subject before"));
            }
        } else {
            return response(ucwords("subject code not valid"));
        }
    }
    function getSubjectsJoinedByUser($id)
    {
        $user = User::find($id);
        $joiner = Joiner::where('email', $user->email)->first();
        if ($joiner) {
            if ($user->email == $joiner->email) {
                return
                    SubjectResource::collection($joiner->subjects);
            }
        } else {
            return response(ucwords("you are not joined to any subjects"));
        }
    }
    function getUsersJoinedTOSubject($id)
    {
        $subject = Subject::where('id', $id)->first();
        return ($subject) ?
            UserResource::collection($subject->joiners) : response("This Subject Does Not Have Joiners");
    }
}