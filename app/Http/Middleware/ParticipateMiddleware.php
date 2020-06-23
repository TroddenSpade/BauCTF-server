<?php

namespace App\Http\Middleware;

use App\Http\Controllers\EventController;
use Closure;
use DateTime;

class ParticipateMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $event = EventController::getLatestEvent();
        $user = auth()->user();
        $request->attributes->add(['event'=>$event]);


        $res = \DB::table('participants')
            ->where([
                ['u_id', '=', $user->id],
                ['e_id', '=', $event->id]
            ])
            ->exists();

        if(!$res){
            return response(['message'=>"not registered for this event"],400);
        }

        $start = DateTime::createFromFormat('Y-m-d H:i:s', $event->start);
        $now = now();
        $isStarted = ($now >= $start);

        if(!$isStarted){
            return response(['message'=>"Event hasn't started yet"],400);
        }

        return $next($request);
    }
}
