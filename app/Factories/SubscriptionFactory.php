<?php


namespace App\Factories;


use App\Company;
use App\Subscription;

class SubscriptionFactory
{

    // Company model
    protected $company;

    // The name of our subscription - we're only using 1 for each company
    protected $name = 'main';

    /**
     * Build Factory instance - we'll need the Company we want to set
     * subcription for
     *
     * SubscriptionFactory constructor.
     * @param Company $company
     */
    public function __construct(Company $company)
    {
        $this->company = $company;
    }

    /**
     * Make a billing subscription for a company
     *
     * @param $company
     * @param $creditCardToken
     * @return mixed
     */
    public static function make($company, $creditCardToken)
    {
        if (!$creditCardToken) abort(400, "No credit card token provided");
        $factory = new static($company, $creditCardToken);
        if ($company->subscribed($factory->name)) abort(500, "Tried to subscribe with existing subscription");
        $subscription = $company->newSubscription($factory->name, $factory->getPlan())->create($creditCardToken);
        $factory->updateQuantity();
        return $subscription;
    }

    /**
     * Determine which plan the company should sign up for
     *
     * @return string
     */
    protected function getPlan()
    {
        if ($this->activeStaffCount() >= 200) {
            return 'enterprise';
        } else {
            return 'growth';
        }
    }

    /**
     * The number of active staff for a company (to be billed)
     *
     * @return int
     */
    protected function activeStaffCount()
    {
        return count($this->company->activeStaff);
    }

    /**
     * Checks to see if a Company's subscription is up to date with
     * it's current parameters and updates it if it isn't.
     *
     * @param $company
     * @return SubscriptionFactory
     */
    public static function updateSubscription($company)
    {
        if (!$subscription = $company->subscription) abort(500, "Tried to update quantity without a subscription");
        $factory = new static($company);
        return $factory->updatePlan()
                       ->updateQuantity();
    }

    /**
     * Update plan for a subcription
     *
     * @return $this
     */
    protected function updatePlan()
    {
        $subscription = $this->company->subscription;
        // Only swap if we're on the plan we should be on
        if ($this->getPlan() !== $subscription->stripe_plan) $subscription->swap($this->getPlan());
        return $this;
    }

    /**
     * Sets the quantity for a subscription
     *
     * @param Subscription $subscription
     * @return $this
     */
    protected function updateQuantity()
    {
        $subscription = $this->company->subscription;
        if ($this->activeStaffCount() >= 200) {
            $quantity = $this->activeStaffCount();
        } else {
            $quantity = 1;
        }

        if($subscription && $subscription->quantity !== $quantity) $subscription->updateQuantity($quantity);

        return $this;
    }
}