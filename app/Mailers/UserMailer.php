<?php
namespace App\Mailers;



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

    public function sendNewUserInvitation($recipientUser)
    {
        $subject = 'Pusaka Jaya - Team Member Invitation';
        $view = 'emails.user.invitation';
        $data = [
            'user' => $recipientUser
        ];

        $this->sendTo($recipientUser->email, $recipientUser->name, $subject, $view, $data);
    }

}