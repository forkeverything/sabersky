<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Pusher;

class PusherController extends Controller
{
    public function postAuthPrivateUserChannel(Request $request)
    {
        if (Auth::check() && $request->channel_name === 'private-user.' . Auth::user()->id) {
            $pusher = new Pusher(env('PUSHER_KEY'), env('PUSHER_SECRET'), env('PUSHER_APP_ID'), ['cluster' => 'ap1']);
            return $pusher->socket_auth($request->channel_name, $request->socket_id);
        }
        return response("could not authenticate pusher", 403);
    }
}
