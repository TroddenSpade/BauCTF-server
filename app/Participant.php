<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Participant
 *
 * @property int $id
 * @property int $u_id
 * @property int $e_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Participant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Participant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Participant query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Participant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Participant whereEId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Participant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Participant whereUId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Participant whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Participant extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'u_id','e_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'timestamp',
    ];
}
