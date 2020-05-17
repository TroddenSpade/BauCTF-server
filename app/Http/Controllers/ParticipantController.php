<?php

namespace App\Http\Controllers;

use App\Participant;
use Illuminate\Http\Request;

class ParticipantController extends Controller{

    public function store(Request $request){

        $valid_data = $request->validate([
            'e_id' => 'required',
        ]);

        $res = \DB::table('events')
            ->where('id', $valid_data['e_id'])
            ->whereDate('end', '>=', now())
            ->exists();

        if ($res) {
            Participant::create(array_merge($valid_data, ['u_id' => auth()->user()->id]));
            return Response(['message' => 'successfully done'], 200);
        }

        return Response(['message' => 'The event has finished'], 400);

    }
}
