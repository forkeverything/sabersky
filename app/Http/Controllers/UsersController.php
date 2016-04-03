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
    public function __construct()
    {
        $this->middleware('auth', [
            'only' => 'api'
        ]);
    }
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

    /**
     * Returns the currently authenticated
     * user.
     *
     * @return mixed
     */
    public function apiGetLoggedUser()
    {
        $user = Auth::user()->load('company', 'role');

        return $user;
    }

    /**
     * Accepts an email (string) and checks to see
     * if it is available.
     * 
     * @param $email
     * @return mixed
     */
    public function getCheckEmailAvailability($email)
    {
        if(User::where('email', $email)->first()) return response("Email already taken", 409);
        return response("OK! Email available", 200);
    }

}
