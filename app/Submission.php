<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Submission
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $e_id
 * @property int $c_id
 * @property int $u_id
 * @property string $flag
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Submission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Submission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Submission query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Submission whereCId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Submission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Submission whereEId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Submission whereFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Submission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Submission whereUId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Submission whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Submission extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'u_id', 'c_id','e_id','flag'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'flag',
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
