<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Submission;
use Faker\Generator as Faker;

$factory->define(Submission::class, function (Faker $faker) {
    $e_id = 4; //TODO: pass this as an argument
    $users = DB::table('participants')->where('e_id',4)->pluck('u_id')->all();
    $answers=['c63abd80e1a216b4d2398c8833ca83b77e8e28c4e9f0c3896b809da072dd85eba4d946945871deaa3727801f18a53bf832d83c26a396473839893ba038c340ab'
    ,'df6530f0218226e79ee534032e76f8a4f5b03ab6ce4b664df0634966092495d442b15baf6ab7a63438e9db2eee79b8b75bb98c732d220c22725a2359c1c5c6c7'
    ,'8b617f400c7c122f2a590852824323b9e4457be58e0b84d2c09de0d369e6ea53a3ed72e41593c59b56f9aba001eb0049d71541f034243741c190c9cbb39134f0'
    ,'457dbd791e38127bf6ace022c98bfd442cf96e92fcf62d76b4d72e8391630ee0a64becab9a21c255570f320c2803286ee215a7472fb8e10b2a2b328fc6763dff'
    ,'457dbd791e38127bf6ace022c98bfd442cf96e92fcf62d76b4d72e8391630ee0a64becab9a21c255570f320c2803286ee215a7472fb8e10b2a2b328fc6763dff'
    ];
    $c_ids=DB::table('challenges')->where('e_id',$e_id)->pluck('id')->all();
    return [
        'u_id'=>$users[array_rand($users)],
        'c_id'=>$c_ids[array_rand($c_ids)],
        'e_id'=>$e_id,
        'flag'=>$answers[array_rand($answers)]
    ];
});
