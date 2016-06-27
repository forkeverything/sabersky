<?php

namespace App\Http\Controllers;

use App\Factories\SubscriptionFactory;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class SubscriptionsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('settings.change');
        $this->middleware('billing', ['except' => 'postNew']);
    }

    /**
     * Create a new payment subscription
     * @param Request $request
     * @return mixed
     */
    public function postNew(Request $request)
    {
        return SubscriptionFactory::make(Auth::user()->company, $request->credit_card_token);
    }

    /**
     * Cancelling a Company's payment subscription
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCancel()
    {
        // Cancel subscription
        Auth::user()->company->subscription->cancel();
        flash()->info('Cancelled billing subscription');
        return redirect()->back();
    }

    /**
     * Resume Company payment subscription...
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postResume()
    {
        Auth::user()->company->subscription->resume();
        flash()->success('Resumed billing subscription');
        return redirect()->back();
    }
}
