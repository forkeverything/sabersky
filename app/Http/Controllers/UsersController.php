<?php

namespace App\Http\Controllers;

use App\Http\Requests\AcceptInvitationRequest;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function showInvitation($inviteKey)
    {
        Auth::logout();
        if($user = User::fetchFromInviteKey($inviteKey)){
            return view('auth.accept', compact('user'));
        };
        // TODO:::Flash error key not valid, please re-send
        flash()->error('Error: Could not accept invitation');
        return redirect('/');
    }

    public function acceptInvitation(AcceptInvitationRequest $request, $inviteKey)
    {
        // Find user
        // Save password
        if ($user = User::fetchFromInviteKey($inviteKey)) {
            $user->update([
                'password' => bcrypt($request->input('password')),
                'invite_key' => ''
            ]);
            flash()->success('Welcome, you have succesfully joined the team!');
            Auth::login($user);
            return redirect(route('singleProject', $user->projects()->first()->id));
        }
        flash()->error('Oops! please resent invitation');
        return redirect('/');
    }

}
