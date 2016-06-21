<?php

namespace App\Providers;

use App\Item;
use App\User;
use Illuminate\Support\ServiceProvider;

class PhotoServiceProvider extends ServiceProvider
{

//    protected $models = ['App\User', 'App\Item'];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
//
//        User::deleting(function ($user) {
//            if ($photo = $user->photo) $photo->remove();
//        });
//
//        Item::deleting(function ($item) {
//            foreach ($item->photos as $photo) {
//                $photo->remove();
//            }
//        });

//        foreach ($this->models as $model) {
//            call_user_func($model . '::deleting', function ($instance) {
//                if (method_exists($instance, 'photo')) {
//                    $instance->photo->remove();
//                } elseif (method_exists($instance, 'photos')) {
//                    foreach ($instance->photos as $photo) {
//                        $photo->remove();
//                    }
//                }
//            });
//        }


    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
