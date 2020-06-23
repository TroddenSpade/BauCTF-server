<?php

namespace App\Http\Controllers;

use App\Event;
use App\Leaderboard;
use Facades\App\Repository\Repository;
use DB;
use Illuminate\Http\Request;

class ScoreboardController extends Controller
{
    public function index()
    {
        \Cache::clear();
        return response(Repository::scoreboard(), 200);
    }

    public function store()
    {
        $scoreboard = ScoreboardController::createScoreboard();
        $event = EventController::getLatestEvent();
        $participants = $scoreboard['participants']->toArray();
        usort($participants, function ($a, $b) {
            if ($a->score > $b->score) return -1;
            if ($b->score > $a->score) return 1;
            if ($a->time > $b->time) return 1;
            if ($a->time < $b->time) return -1;
            return 0;
        });

        foreach ($participants as $index => $p) {
            Leaderboard::create([
                'e_id' => $event->id,
                'u_id' => $p->id,
                'rank' => $index + 1,
                'score' => $p->score
            ]);
        }
        Event::where('id', $event->id)->update(['scoreboard' => $scoreboard]);

        return response(['message'=>'saved'],200);
    }

    public static function createScoreboard()
    {
        $event = EventController::getLatestEvent();

        if(is_null($event))   return ['challenges' => [], 'participants' => []];

        $challenges = DB::table('challenges')
            ->select('challenges.id', 'title',
                DB::raw('COUNT(DISTINCT submissions.u_id , challenges.id) as total'))
            ->from('challenges')
            ->leftJoin('submissions', function ($join) {
                $join
                    ->on('submissions.flag', '=', 'challenges.flag')
                    ->on('submissions.c_id', '=', 'challenges.id');
            })
            ->where('challenges.e_id', '=', $event->id)
            ->groupBy('challenges.id')
            ->get()
            ->keyBy('id');

        $participants = DB::table('users')
            ->select('users.name', 'users.id')
            ->join('participants', function ($join) use ($event) {
                $join
                    ->where('participants.e_id', $event->id)
                    ->on('users.id', '=', 'participants.u_id');
            })
            ->get()
            ->keyBy('id');

        $submissions_query = "
            SELECT challenges.id,submissions.created_at,submissions.u_id
            FROM submissions JOIN challenges
            ON submissions.c_id = challenges.id AND submissions.flag = challenges.flag
            WHERE challenges.e_id = " . $event->id . "
            GROUP BY challenges.id , submissions.u_id
            ";

        $submissions = DB::select(DB::raw($submissions_query));

        $decay = ceil(count($participants) * 1 / 4) | 1;
        $minus = ($event->min_score - $event->max_score) / self::growthFunction($decay);
        foreach ($challenges as $c) {
            $c->score = max(
                ceil($event->max_score + $minus * self::growthFunction($c->total)),
                $event->min_score
            );
        }

        foreach ($participants as $p) {
            $p->score = 0;
            $p->time = 0;
            $p->taskStats = [];
        }

        $start = strtotime($event->start);
        foreach ($submissions as $s) {
            $s->created_at = strtotime($s->created_at) - $start;
            $participants[$s->u_id]->time += $s->created_at;
            $s->created_at = floor($s->created_at / 3600) . ":" . floor($s->created_at / 60) % 60;
            $participants[$s->u_id]->taskStats[$s->id] = $s;
            $participants[$s->u_id]->score += $challenges[$s->id]->score;
        }

        return ['challenges' => $challenges, 'participants' => $participants];
    }

    public static function growthFunction($x)
    {
        return $x + $x ** 1.5;
    }
}
