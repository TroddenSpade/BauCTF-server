<?php

namespace App\Http\Controllers;

use App\Challenge;
use Illuminate\Http\Request;

class ChallengeController extends Controller
{
    public function index(Request $request)
    {
        $event = $request->get('event');
        $participants = \DB::table('participants')
            ->where('e_id',$event->id)
            ->count('id');

        $challenges = Challenge::
            select('challenges.*',
                \DB::raw('CAST(COUNT(DISTINCT submissions.u_id , challenges.id) as UNSIGNED) as total'))
            ->from('challenges')
            ->leftJoin('submissions',function ($join){
                $join
                    ->on('submissions.flag','=','challenges.flag')
                    ->on('submissions.c_id','=','challenges.id');
            })
            ->where('challenges.e_id','=',$event->id)
            ->groupBy('challenges.id')
            ->get();

        $solved = \DB::table('challenges')
        ->select('challenges.id')
            ->join('submissions',function ($join){
                $join
                    ->on('submissions.flag','=','challenges.flag')
                    ->on('submissions.c_id','=','challenges.id');
            })
            ->where([
                ['challenges.e_id','=',$event->id],
                ['submissions.u_id','=',auth()->user()->id]
            ])
            ->get()
            ->keyBy('id');

        $decay = ceil($participants*1/4) | 1;
        $minus = ($event->min_score - $event->max_score)/ScoreboardController::growthFunction($decay);
        foreach ($challenges as $c){
            $c->score = max(
                ceil($event->max_score + $minus*ScoreboardController::growthFunction($c->total)),
                $event->min_score
            );
            if($solved->offsetExists($c->id))
                $c->solved = 1;
            else    $c->solved = 0;
        }

        return response($challenges, 200);
    }

    public function show($cat){
        $challenges = Challenge::
            select('challenges.*','events.name','events.record')
            ->leftJoin('events',function($join){
                $join
                    ->on('challenges.e_id', '=', 'events.id');
            })
            ->where([
                ['events.record', '=', '1'],
                ['challenges.category', '=', $cat]
                ])
            ->get();

        return response($challenges, 200);
    }
}
