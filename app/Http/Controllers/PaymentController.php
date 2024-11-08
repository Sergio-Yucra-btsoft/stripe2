<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function checkout(Request $request)
    {
        $successUrl = config('services.fe.url') . config('services.fe.url_payment');
        $failUrl = config('services.fe.url') . '/payment/fail';

        \Stripe\Stripe::setApiKey(config('stripe.sk'));

        $validated = $request->validate([
            'plan_code' => 'required',
            'type' => 'required|string|in:monthly,annual',
        ]);
        $priceId = $validated['type'] == 'monthly' ? 'price_1QIUkYAY6ndWgOs1vCbX0e5d' : 'price_1QHuclAY6ndWgOs12Vn43X72';
        $session = \Stripe\Checkout\Session::create([
            'mode' => 'subscription', // o payment
            'line_items' => [
                [
                    'price' => $priceId,
                    'quantity' => 1,
                ],
            ],
            'success_url' => $successUrl,
            'cancel_url' => $failUrl,
        ]);
        return response()->json([
            //'url' => $session->url,
            'session' => $session,
        ]);
    }

    public function webhook(Request $request)
    {
        $event = $request->json()->all();
        $eventType = $event['type'];
        $stripe = new \Stripe\StripeClient(config('stripe.sk'));

        if ($eventType === 'payment_intent.succeeded') {
            $description = $event['data']['object']['description'];
            $invoiceId = $event['data']['object']['invoice'];
            $paymentIntentId = $event['data']['object']['id'];

            if ($description === 'Subscription creation') {
                try {
                    $sessions = $stripe->checkout->sessions->all([
                        'payment_intent' => $paymentIntentId,
                        'limit' => 1,
                    ]);
                    $invoice = $stripe->invoices->retrieve($invoiceId);
                    return $invoice;
                    if (!empty($sessions->data)) {
                        $sessionId = $sessions->data[0]->id;
                        
                        return response()->json([
                            'session_id' => $sessionId,
                        ]);
                    } else {
                        Log::warning("No checkout session found for payment intent.");
                    }
                } catch (\Exception $e) {
                    Log::error("Error getting checkout session: " . $e->getMessage());
                }
            } elseif ($description === 'Subscription update') {
                try {
                    $invoice = $stripe->invoices->retrieve($invoiceId);
                    return $invoice;

                } catch (\Exception $e) {
                    Log::error("Error getting checkout session: " . $e->getMessage());
                }
            }
        }

        return response()->json(['status' => 'no_action']);
    }

    public function webhookSubscription(Request $request)
    {
        $event = $request->json()->all();
        $subscription = $event['data']['object']['subscription'];

        return response()->json([
            'subscription' => $subscription,
        ]);
    }

    public function cancelSubscription()
    {
        $subscriptionId = 'sub_1QIEuIAY6ndWgOs1RQi2i3lq';
        $stripe = new \Stripe\StripeClient(config('stripe.sk'));
        try {
            $subscription = $stripe->subscriptions->retrieve($subscriptionId);
            $subscription->cancel();
            return response()->json([
                'message' => 'Subscription cancelled successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }

    }

}
