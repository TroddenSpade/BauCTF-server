<?php


namespace App\Repository;

use App\Http\Controllers\ScoreboardController;
use \Cache;
use \DB;

class Repository
{
    const CACHE_KEY = 'REPO';

    public function all()
    {
        $key = "all";
        $cacheKey = $this->getCacheKey($key);
        return Cache::remember($cacheKey, now()->addHour(), function () {
        });
    }


    public function scoreboard(){
        $key = "SCOREBOARD";
        $cacheKey = $this->getCacheKey($key);
        return Cache::remember($cacheKey, now()->addMinutes(5), function () {
            return ScoreboardController::createScoreboard();
        });
    }

    public function resetScoreboard(){
        $key = "SCOREBOARD";
        $cacheKey = $this->getCacheKey($key);
            Cache::put($cacheKey, ScoreboardController::createScoreboard(), now()->addMinutes(5));
        return Cache::get($cacheKey);
    }

    public function getCacheKey($key)
    {
        $key = strtoupper($key);
        return self::CACHE_KEY . ".$key";
    }
}
