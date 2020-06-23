<?php

namespace App\Http\Controllers;

use App\Participant;
use Illuminate\Http\Request;

class ParticipantController extends Controller{

    public function store(Request $request){

        $valid_data = $request->validate([
            'e_id' => 'required',
            'code' => 'required'
        ]);

        $res = \DB::table('events')
            ->where('id', $valid_data['e_id'])
            ->whereDate('end', '>=', now())
            ->first();


        if ($res) {
            if($res->open == 1 || \Hash::check($valid_data['code'],$res->code)) {
                Participant::create(array_merge($valid_data, ['u_id' => auth()->user()->id]));
                return Response(['message' => 'successfully done'], 200);
            }
            return Response(['message' => 'Wrong entry code'], 400);
        }

        return Response(['message' => 'The event has finished'], 400);

    }
}
