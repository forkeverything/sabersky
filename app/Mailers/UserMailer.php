<?php
namespace App\Mailers;



use App\User;

class UserMailer extends Mailer
{

//    /**
//     * Send a confirmation email when users sign up to
//     * wait list.
//     *
//     * @param $waitlistUser
//     */
//    public function sendConfirmWaitlist($waitlistUser)
//    {
//        $subject = 'Welcome to the club.';
//        $view = 'emails.confirm_waitlist';
//        $data = [];
//
//        $this->sendTo($waitlistUser->email, $waitlistUser->company, $subject, $view, $data);
//    }

    public function sendNewUserInvitation(User $recipient, User $sender)
    {
        $subject = 'Team Member Invitation';
        $view = 'emails.user.invitation';

        $data = compact('recipient', 'sender');

        $this->sendTo($recipient->email, $recipient->name, $subject, $view, $data);
    }

}