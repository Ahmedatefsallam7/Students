<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Creator;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AttendanceController extends Controller
{
    function Attendance()
    {
        $user = User::find(auth()->id());
        $creator = Creator::where('email', $user->email)->first();
        $subjects = Subject::where('creator_id', $creator->id ?? 0)->get();
        if ($creator) {
            return view('Attendance.subjects', compact('subjects'));
        } else {
            return response()
                ->json(['msg' => ucwords('you are not created any subjects')]);
        }
    }
    function getAllAttendances(Request $request)
    {
        $att = DB::table('attendences')
            ->join('subjects', 'subjects.id', 'subject_id')
            ->join('joiners', 'joiners.id', 'joiner_id')
            ->select('joiners.id', 'name', 'sub_name', DB::raw('count(attendences.attend_code) as All_Days'))
            ->where('subjects.id', $request->sub_id)
            ->whereDay('attendences.created_at', now()->day)
            ->groupBy('joiners.id', 'name', 'sub_name')
            ->get();

        return ($att) ? response()->json(['data' => $att])
            : response()->json(['msg' => ucwords('there \'s not attendances for these subject today ')]);
    }
}