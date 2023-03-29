<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Customer;

class StripeController extends Controller
{
    public function form(){
        return view('stripe.form');
    }

    public function makePayment(Request $request){
        $customer = \Stripe\Customer::create([
            'email' => $request->stripeEmail,
            'source' =>  $request->stripeToken,
          ]);

          $charge = \Stripe\Charge::create([
            'customer' => $customer->id,
            'description' => 'T-shirt',
            'amount' => 500,
            'currency' => 'usd',
          ]);
    }

    public function createCustomer(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'source' => $request->stripeToken,
        ]);

        return $customer;
    }
}
