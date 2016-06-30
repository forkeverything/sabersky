<?php


namespace App\Utilities\Traits;


use App\Activity;

trait LineItemsActivities
{
    /**
     * Over-write activities() so we can pull in relevant
     * Line Item activities too
     *
     * @return mixed
     */
    public function getActivitiesAttribute()
    {
        $activites = $this->getAllActivities();
        $this->setRelation('activities', $activites);
        return $this->getRelation('activities');
    }

    /**
     * Get all activities - including Line Item activities
     *
     * @return mixed
     */
    public function getAllActivities()
    {
        $modelActivities = $this->modelActivities;
        $LIActivities = $this->lineItemsActivities();
        return $modelActivities->merge($LIActivities);
    }

    /**
     * Renamed relationship to activities to get this model's activities
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function modelActivities()
    {
        return $this->morphMany(Activity::class, 'subject');
    }


    /**
     * Get all the relevant Line Item activities
     *
     * @return \Illuminate\Support\Collection
     */
    public function lineItemsActivities()
    {
        $relevantActivities = [
            'paid_line_item',
            'received_line_item'
        ];

        $activities = [];
        foreach ($this->lineItems as $lineItem) {
            foreach ($relevantActivities as $relevantActivity) {
                if($activity =  $lineItem->activities->where('name', $relevantActivity)->first()) array_push($activities, $activity);
            }
        }
        return collect($activities)->sortBy('created_at');
    }
}