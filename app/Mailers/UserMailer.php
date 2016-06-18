<?php
namespace App\Mailers;



use App\Company;
use App\Project;
use App\PurchaseOrder;
use App\PurchaseRequest;
use App\User;

class UserMailer extends Mailer
{

    /**
     * Send Welcome email to users when they first register their
     * company
     *
     * @param Company $company
     * @param User $user
     */
    public function sendWelcomeEmail(Company $company, User $user)
    {
        $subject = 'Welcome!';
        $view = 'emails.user.welcome';
        $this->sendTo($user->email, $user->name, $subject, $view, compact('company', 'user'));
    }
    
    /**
     * Invitation email that gets sent to staff from same company
     * 
     * @param User $recipient
     * @param User $sender
     */
    public function sendNewUserInvitation(User $recipient, User $sender)
    {
        $subject = 'Team Member Invitation';
        $view = 'emails.user.invitation';

        $data = compact('recipient', 'sender');

        $this->sendTo($recipient->email, $recipient->name, $subject, $view, $data);
    }

    /**
     * Confirmation email to User that's been added to a Project
     * 
     * @param Project $project
     * @param User $addedUser
     * @param User $managingUser
     */
    public function sendConfirmAddedToProject(Project $project, User $addedUser, User $managingUser)
    {
        $subject = 'Added to Project - ' . ucfirst($project->name);
        $view = 'emails.projects.confirm';
        $this->sendTo($addedUser->email, $addedUser->name, $subject, $view, compact('project', 'addedUser', 'managingUser'));
    }

    /**
     * PR notification to those that can submit Orders
     * 
     * @param PurchaseRequest $purchaseRequest
     * @param User $recipient
     * @param User $requester
     */
    public function sendPurchaseRequestNotification(PurchaseRequest $purchaseRequest, User $recipient, User $requester)
    {
        $subject = 'New Purchase Request (' . ucfirst($purchaseRequest->project->name) . ')';
        $view = 'emails.purchase_requests.new';
        $this->sendTo($recipient->email, $recipient->name, $subject, $view, compact('purchaseRequest', 'recipient', 'requester'));
    }

    /**
     * PO Notification to those that can approve the Order
     * @param PurchaseOrder $purchaseOrder
     * @param User $recipient
     * @param User $submitter
     */
    public function sendPurchaseOrderNotification(PurchaseOrder $purchaseOrder, User $recipient, User $submitter)
    {
        $subject = 'New Purchase Order';
        $view = 'emails.purchase_orders.new';
        $this->sendTo($recipient->email, $recipient->name, $subject, $view, compact('purchaseOrder', 'recipient', 'submitter'));
    }

}