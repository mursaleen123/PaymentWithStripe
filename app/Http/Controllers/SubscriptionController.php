<?php

namespace App\Http\Controllers;

use App\Models\Plan as ModelsPlan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Cashier;
use Stripe\Plan;
use \Stripe\Stripe;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function singleCharge(Request $request)
    {
        $amount = $request->amount * 100;
        $payment_method =  $request->payment_method;

        $user =  auth()->user();
        $user->createOrGetStripeCustomer();
        $payment_method = $user->addPaymentMethod($payment_method);
        $user->charge($amount, $payment_method->id);

        return redirect()->route('home');
    }

    public function showPlanForm()
    {
        return view('stripe.plans.create');
    }

    public function savePlan(Request $request)
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        $amount = $request->amount * 1000;
        try {
            $plan = Plan::create([
                'amount' => $amount,
                'currency' => $request->currency,
                'interval' => $request->billing_period,
                'interval_count' => $request->interval,
                'product' => [
                    'name' => $request->name
                ]
            ]);
            // dd($plan);
            ModelsPlan::create([
                'plan_id' => $plan->id,
                'name' => $request->name,
                'price' => $plan->amount,
                'billing_method' => $plan->interval,
                'interval_count' => $plan->interval_count,
                'currency' => $plan->currency,

            ]);
        } catch (Exception $ex) {
            dd($ex->getMessage());
        }

        return redirect()->back();
    }
    public function allPlans()
    {
        $basic = ModelsPlan::where('name', 'basic')->first();
        $professional = ModelsPlan::where('name', 'professional')->first();
        $enterprise = ModelsPlan::where('name', 'enterprise')->first();

        return view('stripe.plans.plans')->with([
            'basic' => $basic,
            'professional' => $professional,
            'enterprise' => $enterprise,
        ]);
    }
    public function checkoutPlan($planID)
    {
        $plan = ModelsPlan::where('plan_id', $planID)->first();

        if (!$plan) {
            return back()->with([
                'message' => 'Unable to Locate the Plan'
            ]);
        }
        return view('stripe.plans.checkout')->with([
            'plan' => $plan,
            'intent' => auth()->user()->createSetupIntent(),
        ]);
    }
    public function checkoutProcess(Request $request)
    {

        $user =  auth()->user();
        $user->createOrGetStripeCustomer();
        $payment_method = $request->payment_method;
        $PaymentMethod = null;
        if ($payment_method != null) {
            $PaymentMethod = $user->addPaymentMethod($payment_method);
        }

        $plan = $request->plan_id;
        try {
            $user->newSubscription('default', $plan)->create($PaymentMethod != null ? $payment_method : '');
        } catch (Exception $ex) {
            dd($ex->getMessage());
        }
        $request->session()->flash('alert-success', 'You are subscribed to this Plan');

        return 'success';

    }
}
