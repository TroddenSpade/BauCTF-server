<?php

namespace App\Http\Controllers;

use App\Events\RefreshScoreboard;
use \DB;
use App\Submission;
use DateTime;
use Illuminate\Http\Request;
use Facades\App\Repository\Repository;

class SubmissionController extends Controller
{
    public function store(Request $request)
    {
        $valid_data = $request->validate([
            'c_id' => 'required',
            'flag' => 'required',
        ]);

//        \Cache::clear();
        $event = $request->get('event');
        $end = DateTime::createFromFormat('Y-m-d H:i:s', $event->end);
        $now = now();

        if ($now <= $end) {
            $challenge = DB::table('challenges')
                ->where('id', '=', $valid_data['c_id'])
                ->where('e_id', '=', $event->id)
                ->first();
            if (!is_null($challenge)) {
                $valid_data['flag'] = hash('sha512',$valid_data['flag']);
                Submission::create(array_merge($valid_data,
                    ['e_id' => $event->id,'u_id'=>auth()->user()->id]));

                if ($valid_data['flag'] == $challenge->flag) {
//                    event(new RefreshScoreboard(Repository::resetScoreboard()));
                    return Response(['message'=>'correct flag'], 200);
                }

                return Response(['message'=>'wrong flag'], 201);
            }

            return Response(['message' => 'challenge does not exist!'], 400);
        }

        return Response(['message'=>'Event has finished'], 401);
    }

    public function index(){
        $submissions = Submission::
            select('challenges.title','challenges.category','events.name','submissions.created_at')
            ->join('challenges',function($join){
                $join
                    ->on('challenges.id','=','submissions.c_id')
                    ->on('challenges.flag','=','submissions.flag');
            })
            ->leftJoin('events','submissions.e_id','=','events.id')
            ->where('submissions.u_id','=',auth()->user()->id)
            ->groupBy('challenges.id')
            ->orderBy('submissions.id','desc')
            ->take(10)
            ->get();


        return response($submissions,200);
    }
}
