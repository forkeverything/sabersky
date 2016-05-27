<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
