<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Challenge
 *
 * @property int $id
 * @property int $e_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $flag
 * @property string $attachment
 * @property string $title
 * @property string $author
 * @property string $body
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Challenge newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Challenge newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Challenge query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Challenge whereAttachment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Challenge whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Challenge whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Challenge whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Challenge whereEId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Challenge whereFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Challenge whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Challenge whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Challenge whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Challenge extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'e_id', 'category', 'attachment', 'title', 'author', 'body',
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

    ];
}
