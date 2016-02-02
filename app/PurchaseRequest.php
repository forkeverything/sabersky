<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseRequest extends Model
{
    protected $fillable = [
        'quantity',
        'due',
        'state',
        'urgent',
        'project_id',
        'item_id',
        'user_id'
    ];

    protected $dates = [
        'due'
    ];


    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function sortFilter($user, $sort, $order, $filter, $urgent)
    {

        $query =  $user->company->purchaseRequests();

        if ($urgent) {
            $query->where('urgent', 1);
        }

        $query->whereState($filter);

        switch($sort) {
            case 'due_date':
                return self::scopeSortDue($query, $order);
                break;
            case 'project':
                return self::scopeSortProjectName($query, $order);
                break;
            case 'item':
                return self::scopeSortItemName($query, $order);
                break;
            case 'quantity':
                return self::scopeSortQuantity($query, $order);
                break;
            case 'user':
                return self::scopeSortUserName($query, $order);
                break;
            case 'time_requested':
                return self::scopeSortTimeRequested($query, $order);
                break;
            default:
                return $query->latest()->get();
        }

    }


    public static function scopeSortDue($query, $order = 'asc')
    {
        return $query->orderBy('due', $order)
            ->with('project', 'item', 'user')
            ->get();
    }

    public static function scopeSortQuantity($query, $order = 'asc')
    {
        return $query->orderBy('quantity', $order)
            ->with('project', 'item', 'user')
            ->get();
    }

    public static function scopeSortProjectName($query, $order = 'asc')
    {
        return $query->orderBy('projects.name', $order)
            ->with('project', 'item', 'user')
            ->get(['purchase_requests.*']);
    }

    public static function scopeSortItemName($query, $order = 'asc')
    {
        return $query->join('items', 'purchase_requests.item_id', '=', 'items.id')
            ->orderBy('items.name', $order)
            ->with('project', 'item', 'user')
            ->get(['purchase_requests.*']);
    }

    public static function scopeSortUserName($query, $order = 'asc')
    {
        return $query->join('users', 'purchase_requests.user_id', '=', 'users.id')
            ->orderBy('users.name', $order)
            ->with('project', 'item', 'user')
            ->get(['purchase_requests.*']);
    }

    public static function scopeSortTimeRequested($query, $order = 'asc')
    {
        return $query->orderBy('created_at', $order)
            ->with('project', 'item', 'user')
            ->get();
    }

    public function getStateAttribute($property)
    {
        return ucfirst($property);
    }


}
