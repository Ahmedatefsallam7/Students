<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Joiner;
use App\Models\Creator;
use App\Models\Subject;
use App\Models\Attendence;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\SubjectResource;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Controller
{

    function index()
    {
        $subs = Subject::all();
        return count($subs) > 0 ?
            SubjectResource::collection($subs)
            : response()->json(["msg" => ucwords("No subjects Added yet")]);
    }
    function getSubject($id)
    {
        $sub = Subject::find($id);
        if ($sub) {
            if (auth()->user()->id == $sub->creator_id) {

                return new SubjectResource($sub);
            }
            return response()->json(["msg" => ucwords('not authorize to get this subject')]);
        }
        return response()->json(["msg" => ucwords("this Subject With Id $id Not Found")]);
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
        if ($subject) {

            $subject->update([
                'sub_name' => $request->sub_name,
                'sub_code' => Str::random(6),
            ]);
            return response()->json(["msg" => "Subject Updated successfully"]);
        } else {
            return response()->json(["msg" => " This Subject Not Found"]);
        }
    }

    function destroy($id)
    {
        $sub = Subject::find($id);
        if ($sub) {
            if (auth()->user()->id === $sub->creator_id) {
                $sub->delete();
                return response()->json(["msg" => ucwords(" Subject Deleted Successfully")]);
            }
            return response()->json(["msg" =>  ucwords("not authorized to delete this subject")]);
        }
        return response()->json(["msg" =>  ucwords("Cannot find this subject to delete it")]);
    }



    function restoreSubject($id)
    {
        $sub = Subject::onlyTrashed()->where('id', $id)->first();
        if ($sub) {
            if (auth()->user()->id === $sub->creator_id) {
                $sub->restore();
                return response()->json(["msg" => ucwords("Subject Restored Successfully")]);
            }
            return response()->json(["msg" => ucwords("not authorized to restore this subject")]);
        }
        return response()->json(["msg" => ucwords("Cannot find this subject to restore it")]);
    }



    function restoreAll()
    {
        $trashed = Subject::withTrashed();
        if ($trashed) {
            $trashed->restore();
            return response()->json(["msg" => ucwords("All Subjects Restored Successfully")]);
        }
        return response()->json(["msg" => ucwords("There is not deleted subjects to restore it")]);
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

        $creator = Creator::where('email', auth()->user()->email)->first();
        $subject = Subject::where('id', $request->sub_id)->first();

        if ($subject && $creator) {
            if ($creator->id == $subject->creator_id) {
                $subject->update([
                    'attend_code' => $code,
                ]);
                return new SubjectResource($subject);
            } else {
                return response()
                    ->json(['msg' => 'You Are Not Created This Subject  ' . $subject->sub_name]);
            }
        } else {
            return response()->json(['msg' => 'This Subject Not Exists']);
        }
    }

    // function attendMe($id)
    // {
    //     $user = User::find($id);
    //     $joiner = Joiner::where('email', $user->email)->first();
    //     if ($joiner) {
    //         $subjects = $joiner->subjects->all();
    //         return view("subjects.joinedSubjects", compact("subjects"));
    //     } else {
    //         return response(ucwords('you do not joined to any subject'));
    //     }
    // }

    // function joinSubject(Request $request)
    // {
    //     $counter = 0;
    //     $subject = Subject::where('id', $request->sub_id)->first();

    //     if ($subject->attend_code) {
    //         return view("subjects.attendCode", compact('subject'));
    //     } else {
    //         return response(ucwords('this subject dose not have attend code'));
    //     }
    // }
    function checkCode(Request $request)
    {
        $user = User::find(auth()->id());
        $joiner = Joiner::where('email', $user->email)->first();
        $subjectCode = Subject::where('attend_code', $request->check)->first();
        $takeAttend = Subject::where('take_attend', 1)
        ->where('attend_code', $request->check)
        ->first();

        $count_days = DB::table('subject_joiners')
            ->where('joiner_id', $joiner->id)
            ->where('subject_id', $subjectCode->id ?? 1)
            ->first();

        $oldAttendance = Attendence::where('joiner_id', $joiner->id)
            ->where('subject_id', $subjectCode->id ?? 1)
            ->where('attend_code', $subjectCode->attend_code ?? 1)
            ->first();

        if ($takeAttend) {
            if ($subjectCode) {
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
                    return response()->json(['msg' => ucwords('you are attended now')]);
                } else {
                    return response()->json(['msg' => ucwords('you are attended before with this attend code')]);
                }
            } else {
                return  response()->json(['msg' => ucwords('this code not valid')]);
            }
        } else {
            return response()->json(['msg' => ucwords('timer not start yet')]);
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
        $creator = Creator::where('email', auth()->user()->email)->first();
        if ($creator) {
            if ($close) {
                $sub->update([
                    'take_attend' => 0,
                ]);
                return response()
                    ->json(['msg' => ucwords('timer not active now to subject ' . $sub->sub_name)]);
            } else {
                $sub->update([
                    'take_attend' => 1,
                ]);
                return response()
                    ->json(['msg' => ucwords('timer active now to subject ' . $sub->sub_name)]);
            }
        } else {
            return response()
                ->json(['msg' => ucwords('you don not created any subjects ')]);
        }
    }
}