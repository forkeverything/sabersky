<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Activity
 *
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer $subject_id
 * @property string $subject_type
 * @property string $name
 * @property integer $user_id
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $subject
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\Activity whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Activity whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Activity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Activity whereSubjectId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Activity whereSubjectType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Activity whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Activity whereUserId($value)
 * @mixin \Eloquent
 */
class Activity extends Model
{
    protected $fillable = [
        'subject_id',
        'subject_type',
        'name',
        'user_id'
    ];

    /**
     * Always append these relationships to every instance. If we're
     * displaying the Activity, we'll assume we want to always know
     * who performed it and what it was for
     *
     * @var array
     */
    protected $with = [
        'user',
        'subject'
    ];

    /**
     * The subject the Activity was performed on
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function subject()
    {
        return $this->morphTo('subject');
    }

    /**
     * The User that performed the activity
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
