<?php

namespace App;

use App\Utilities\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Note
 *
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $content
 * @property integer $subject_id
 * @property string $subject_type
 * @property integer $user_id
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $subject
 * @property-read \App\User $poster
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Activity[] $activities
 * @method static \Illuminate\Database\Query\Builder|\App\Note whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Note whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Note whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Note whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Note whereSubjectId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Note whereSubjectType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Note whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Note latest()
 * @mixin \Eloquent
 */
class Note extends Model
{

    use RecordsActivity;

    protected $fillable = [
        'content',
        'user_id'
    ];

    protected $with = [
        'poster'
    ];

    /**
     * The subject the note is referring to: Item, Vendor etc..
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function subject()
    {
        return $this->morphTo();
    }

    /**
     * The user that posted the note
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function poster()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Query scope that retrieves notes from latest -> oldest
     * 
     * @param $query
     * @return mixed
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

}
