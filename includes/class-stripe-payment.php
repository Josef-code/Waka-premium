<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/../vendor/autoload.php';
require_once plugin_dir_path(__FILE__) . '../includes/class-mailer-lite.php';

class Waka_Stripe_Payment
{
    /**
     * Constructor to initialize the class and register hooks
     */
    public function __construct()
    {
        // Register the redirect action
        add_action('user_created_redirect', [$this, 'redirect_to_stripe'], 10, 2);

        // Register the REST API route for Stripe webhook
        add_action('rest_api_init', function () {
            register_rest_route('wakaforafricans/v1', '/stripe-webhook', array(
                'methods' => 'POST',
                'callback' => [$this, 'handle_stripe_webhook'],
                'permission_callback' => '__return_true', // Adjust this for security
            ));
        });
    }

    /**
     * Redirect to Stripe Checkout
     *
     * @param string $user_email
     * @param string $subscription_id
     * 
     * The subscription id is the product price ID in stripe so you can change it in the subscription selection pages in registeration or page-pro-plan
     */
    public function redirect_to_stripe($user_email, $subscription_id)
    {
        \Stripe\Stripe::setApiKey(STRIPE_KEY);

        $session = \Stripe\Checkout\Session::create([
            'success_url' => home_url('/login?payment=successful'),
            'cancel_url' => home_url('/login'),
            'mode' => 'subscription',
            'customer_email' => $user_email,
            'line_items' => [
                [
                    'price' => $subscription_id,
                    'quantity' => 1,
                ]
            ],
        ]);

        // Check if the session was created successfully
        if ($session && isset($session->url)) {
            wp_redirect($session->url);
            exit; // Always call exit after wp_redirect
        } else {
            echo 'Error creating Stripe session.';
            exit; // Exit if there's an error
        }
    }

    /**
     * Handle Stripe webhook events
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function handle_stripe_webhook(WP_REST_Request $request)
    {
        //Setup Mailerlite API
        $mailerLite = new Waka_Mailer_Lite();

        // Set your secret key
        \Stripe\Stripe::setApiKey(STRIPE_KEY); // Replace with your secret key

        // Retrieve the request body and signature header
        $payload = $request->get_body();
        $sig_header = $request->get_header('Stripe-Signature');
        $webhook_secret = STRIPE_SECRET; // Replace with your webhook secret

        $event = null;

        // Verify the webhook signature
        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $webhook_secret);
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return new WP_REST_Response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return new WP_REST_Response('Invalid signature', 400);
        }

        // Handle the event
        if ($event && isset($event->type)) {
            switch ($event->type) {
                case 'checkout.session.completed':
                    // Handle successful subscription creation
                    break;

                case 'invoice.paid':
                    $this->update_subscription_status($event->data->object->customer_email, 'active');
                    $mailerLite->handleSubscription($event->data->object->customer_email, $event->data->object->customer_name, 134440088151000705);
                    break;

                case 'invoice.payment_failed':
                    $this->update_subscription_status($event->data->object->customer_email, 'inactive');
                    $mailerLite->removeSubscriber(134440088151000705, $event->data->object->customer_email);
                    break;

                // Add more cases for other event types as needed
            }
        }

        // Return a response to Stripe
        return new WP_REST_Response('Webhook received', 200);
    }



    /**
     * Update the user's subscription status
     *
     * @param string $customer_email
     * @param string $status
     */
    private function update_subscription_status($customer_email, $status)
    {
        $user = get_user_by('email', $customer_email);
        if (!empty($user)) {
            update_user_meta($user->ID, 'subscription_status', $status);
        }
    }

    // public function handle_stripe_webhook_no_security(WP_REST_Request $request)
    // {
    //     // Get the body of the request
    //     $body = $request->get_body();
    //     $data = json_decode($body, true);

    //     // Handle the webhook event
    //     if (isset($data['type'])) {
    //         switch ($data['type']) {
    //             case 'checkout.session.completed':
    //                 // Handle successful subscription creation
    //                 break;

    //             case 'invoice.paid':
    //                 $this->update_subscription_status($data['data']['object']['customer_email'], 'active');
    //                 break;

    //             case 'invoice.payment_failed':
    //                 $this->update_subscription_status($data['data']['object']['customer_email'], 'inactive');
    //                 break;

    //             // Add more cases for other event types as needed
    //         }
    //     }

    //     // Return a response to Stripe
    //     return new WP_REST_Response('Webhook received', 200);
    // }
}


