<?php


namespace App\Utilities\Traits;


use App\Activity;
use App\User;
use ReflectionClass;

trait RecordsActivity
{

    /**
     * Relationship to see activities for any model that records activities
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function activities()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    /**
     * Register our model events
     */
    protected static function bootRecordsActivity()
    {
        // For each of our events
        foreach (static::getModelEvents() as $event) {
            // ie. static::created - which lives on the model
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }
    }

    // Get a list of the Model events we want to record
    protected static function getModelEvents()
    {
        // If we specified on the model which events to only record
        if(isset(static::$recordEvents)) return static::$recordEvents;

        // Default list of model events to listen to
        return [
            'created', 'updated', 'deleted'
        ];
    }

    /**
     * The function that actually creates the Activity
     * and records it in DB
     *
     * @param $event
     * @param User $user
     */
    public function recordActivity($event, User $user = null)
    {
        // We assume the owner of the model is the one that initiated the activity
        // this works if model belongsTo a User
        $userId = $this->user_id;
        // Otherwise we can manually pass in a User and over-ride the id
        if($user) $userId = $user->id;
        // If we haven't got a user by now, lose all hope
        if(! $userId) return;
        Activity::create([
            'subject_id' => $this->id,
            'subject_type' => get_class($this),
            'name' => $this->getActivityName($this, $event),
            'user_id' => $userId
        ]);
    }

    /**
     * Generates an activity name for our specific action and model
     *
     * @param $model
     * @param $action
     * @return string
     */
    protected function getActivityName($model, $action)
    {
        // Get the class name of the model
        $name = str_to_snake((new ReflectionClass($model))->getShortName());


        // append our action and return
        return "{$action}_{$name}";
    }

}