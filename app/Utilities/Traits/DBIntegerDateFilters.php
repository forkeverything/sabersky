<?php
namespace App\Utilities\Traits;

use Carbon\Carbon;

trait DBIntegerDateFilters
{
    /**
     * A filter function for a column who's type is Integer. The
     * limits are all inclusive (ie. ... or equal to). We also
     * set the property by combining column name & '_filter'
     *
     * @param $column
     * @param $range
     * @return $this
     */
    public function filterIntegerField($column, $range)
    {
        // Create property dynamically with {} !
        $rangeArray = $range ? $this->{$column . '_filter_integer'} = explode(' ', $range) : null;
        $this->queryColumnRange($column, $rangeArray);
        return $this;
    }

    /**
     * Similar to filterIntegerField() except we're doing this for a aggregate
     * column, using HAVING...
     *
     * @param $column
     * @param $range
     * @return $this
     */
    public function filterAggregateIntegerColumn($column, $range)
    {
        if(!$range) return $this;
        $rangeArray = $this->{$column . '_filter_aggregate_integer'} = explode(' ', $range);
        if(count($rangeArray) < 1) return $this;

        $min = $rangeArray[0] ?: null;
        $max = $rangeArray[1] ?: null;
        if($min) $this->query->having($column, '>=', $min);
        if($max) $this->query->having($column, '<=', $max);

        return $this;
    }

    /**
     * Filter a column: date
     * @param $column
     * @param $range
     * @return $this
     */
    public function filterDateField($column, $range)
    {
        $rangeArray = $range ? $this->{$column . '_filter_date'} = explode(' ', $range) : null;

        // If max value is a DATE - let's add another day so it counts the FULL day
        if ($rangeArray[1]) $rangeArray[1] = Carbon::createFromFormat('Y-m-d', $rangeArray[1])->addDay(1);

        $this->queryColumnRange($column, $rangeArray);

        return $this;
    }

    /**
     * Actual function that performs a query for a column field
     * which accepts an array of 2 values (min & max). Each
     * value is optional
     *
     * @param $column
     * @param $rangeArray
     */
    protected function queryColumnRange($column, $rangeArray)
    {
        if ($rangeArray && count($rangeArray) > 1) {
            $min = $rangeArray[0] ?: null;
            $max = $rangeArray[1] ?: null;
            if ($min && $max) {
                $this->query->whereBetween($column, [$min, $max]);
            } else if ($min && !$max) {
                $this->query->where($column, '>=', $min);
            } else if (!$min && $max) {
                $this->query->where($column, '<=', $max);
            }
        }
    }
}