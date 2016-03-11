<?php

namespace App;

use App\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Rule extends Model
{
    public function property()
    {
        return DB::table('properties')->select('*')->where('id', $this->property_id)->get();
    }

    public function trigger()
    {
        return DB::table('triggers')->select('*')->where('id', $this->trigger_id)->get();
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function purchaseOrders()
    {
        return $this->belongsToMany(PurchaseOrder::class);
    }
}
