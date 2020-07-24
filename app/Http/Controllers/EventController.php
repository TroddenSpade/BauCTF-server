<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(){
        $events = Event::
            select('events.*',
                \DB::raw('COUNT(DISTINCT participants.u_id , events.id) as participated'))
            ->leftJoin('participants',function($join){
                $join
                    ->on('participants.e_id','=','events.id')
                    ->where('participants.u_id','=',auth()->user()->id);
            })
            ->groupBy('events.id')
            ->orderBy('events.id','desc')
            ->get();
        return response($events,200);
    }


    public function show($id){
        $event = Event::
            where('id',$id)
            ->first();
        return response($event,200);
    }

    public static function getLatestEvent(){
        $event = Event::whereDate('end','>=',now())
            ->orderBy('id','asc')
            ->first();

        if($event){
            return $event;
        }

        return Event::orderBy('id','desc')
            ->first();
    }

    public function latest(){
        return response(['event'=>self::getLatestEvent()],200);
    }
}
