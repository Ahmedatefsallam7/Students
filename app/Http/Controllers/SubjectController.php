<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubjectResource;
use App\Models\Attendence;
use App\Models\Creator;
use App\Models\Joiner;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SubjectController extends Controller
{
    function index()
    {
        $subs = Subject::all();
        return count($subs) > 0 ?
            SubjectResource::collection($subs) : ucwords("No subjects Added yet");
    }

    function getSubject($id)
    {
        $sub = Subject::find($id);
        if ($sub) {
            if ($sub->creator->id == $sub->creator_id) {

                return new SubjectResource($sub);
            }
            return ucwords('not authorize to get this subject');
        }
        return ucwords("this Subject With Id $id Not Found");
    }

    function show()
    {
        $subjects = Subject::get();
        return view("subjects.index", compact('subjects'));
    }

    function edit($id)
    {
        $subject = Subject::find($id);
        if ($subject) {
            if ($subject->creator_id == auth()->user()->id) {
                return  view('subjects.editSub', compact('subject'));
            }
            return ucwords("Not authorize to edit this subject");
        }
        return ucwords('This subject not Found to Edit');
    }

    function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'sub_name' => 'required|string|min:2|max:10|unique:subjects',
            'sub_code' => 'unique:subjects',
        ]);

        if ($validator->fails()) {
            return  $validator->errors();
        }
        $subject = Subject::find($id);
        $subject->update([
            'sub_name' => $request->sub_name,
            'sub_code' => Str::random(6),
        ]);
        return back();
    }

    function destroy($id)
    {
        $sub = Subject::find($id);
        if ($sub) {
            if (auth()->user()->id === $sub->creator_id) {
                $sub->delete();
                return ucwords(" Subject Deleted Successfully");
            }
            return ucwords("not authorized to delete this subject");
        }
        return ucwords("Cannot find this subject to delete it");
    }
    function restoreSubject($id)
    {
        $sub = Subject::onlyTrashed()->where('id', $id)->first();
        if ($sub) {
            if (auth()->user()->id === $sub->creator_id) {
                $sub->restore();
                return ucwords("Subject Restored Successfully");
            }
            return ucwords("not authorized to restore this subject");
        }
        return ucwords("Cannot find this subject to restore it");
    }


    function restoreAll()
    {
        $trashed = Subject::withTrashed();
        if ($trashed) {
            $trashed->restore();
            return ucwords("All Subjects Restored Successfully");
        }
        return ucwords("There is not deleted subjects to restore it");
    }

    //////////////////////////////////////////////////////////////////////////////////////////////
    function selectSubject()
    {
        $user = User::find(auth()->id());
        $creator = Creator::where("email", $user->email)->first();
        if ($creator) {

            $subjects = Subject::where('creator_id', $creator->id)->get();
            return ($subjects) ? view("subjects.select_subject", compact("subjects"))
                : response(ucwords('you do not create any subjects'));
        }
        return response(ucwords('you did\'t created any subjects to generate  attend code'));
    }

    function GenerateCode(Request $request)
    {
        $code = random_int(100000, 999999);

        $validator = Validator::make($request->all(), [
            'attend_code' => 'unique:subjects',
        ]);
        if ($validator->fails()) {
            return  $validator->errors();
        }

        $subject = Subject::where('id', $request->sub_id)->first();
        $subject->update([
            'attend_code' => $code,
        ]);
        return $subject;
    }

    function attendMe($id)
    {
        $user = User::find($id);
        $joiner = Joiner::where('email', $user->email)->first();
        if ($joiner) {
            $subjects = $joiner->subjects->all();
            return view("subjects.joinedSubjects", compact("subjects"));
        } else {
            return response(ucwords('you do not joined to any subject'));
        }
    }

    function joinSubject(Request $request)
    {
        $counter = 0;
        $subject = Subject::where('id', $request->sub_id)->first();

        if ($subject->attend_code) {
            return view("subjects.attendCode", compact('subject'));
        } else {
            return response(ucwords('this subject dose not have attend code'));
        }
    }
    function checkCode(Request $request)
    {
        $user = User::find(auth()->id());
        $joiner = Joiner::where('email', $user->email)->first();
        $subjectCode = Subject::where('attend_code', $request->check)->first();
        $takeAttend = Subject::where('take_attend', 1)->first();

        // ->with(['attendences', 'joiners'])->first();

        $count_days = DB::table('subject_joiners')
            ->where('joiner_id', $joiner->id)
            ->where('subject_id', $subjectCode->id ?? 1)
            ->first();

        $oldAttendance = Attendence::where('joiner_id', $joiner->id)
            ->where('subject_id', $subjectCode->id ?? 1)
            ->where('attend_code', $subjectCode->attend_code ?? 1)
            ->first();

        if ($subjectCode) {
            if ($takeAttend) {
                if ($joiner &&  !$oldAttendance) {
                    DB::table('subject_joiners')
                        ->where('joiner_id', $joiner->id)
                        ->where('subject_id', $subjectCode->id)
                        ->update([
                            'count_days' => ++$count_days->count_days,
                        ]);
                    Attendence::create([
                        'joiner_id' => $joiner->id,
                        'subject_id' => $subjectCode->id,
                        "attend_code" => $subjectCode->attend_code,
                    ]);
                    return response(ucwords('you are attended now'));
                } else {
                    return response(ucwords('you are attended before with this attend code'));
                }
            } else {
                return response(ucwords('timer not start yet'));
            }
        } else {
            return  response(ucwords('this code not valid'));
        }
    }

    function OpenTimer($close = null)
    {
        $user = User::find(auth()->id());
        $creator = Creator::where('email', $user->email)->first();
        if ($creator) {
            $subjects = $creator->subject;
            return view(
                'subjects.select_subject2',
                compact('subjects', 'close' ?? null)
            );
        } else {
            return response(ucwords('you don not created any subjects'));
        }
    }
    function start(Request $request, $close = null)
    {
        $sub = Subject::where('id', $request->sub_id)->first();
        if ($close) {
            $sub->update([
                'take_attend' => 0,
            ]);
            return response(ucwords('timer not active now to subject ') . $sub->sub_name);
        } else {
            $sub->update([
                'take_attend' => 1,
            ]);
            return response(ucwords('timer active now to subject ') . $sub->sub_name);
        }
    }
}
