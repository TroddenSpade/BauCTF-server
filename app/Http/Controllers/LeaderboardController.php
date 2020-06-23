<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function index(){
        $leaders = Event::
            select('leaderboards.*','events.name','events.start')
            ->leftJoin('leaderboards',function($join){
                $join
                    ->on('events.id','=','leaderboards.e_id')
                    ->where('leaderboards.u_id',auth()->user()->id);
            })
            ->get();

        return response($leaders,200);
    }


}
