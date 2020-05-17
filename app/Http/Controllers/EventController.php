<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventController extends Controller
{
    public static function getLatestEvent(){
        $event = \DB::table('events')
            ->whereDate('end','>=',now())
            ->orderBy('id','asc')
            ->first();

        if($event){
            return $event;
        }

        return \DB::table('events')->first();
    }
}
