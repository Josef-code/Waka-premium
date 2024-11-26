<?php
/**
 * Plugin Name: Waka Premium
 * Description: Restrict access to posts based on subscription status.
 * Version: 1.0
 * Author: Joseph Bassey
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Include the content restriction class
require_once plugin_dir_path(__FILE__) . 'includes/class-content-restriction.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-content-update-subscription.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-stripe-payment.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-mailer-lite.php';

// Main plugin class
class Waka_Premium
{

    public function __construct()
    {
        // Initialize the content restriction logic
        new Waka_Content_Restriction();

        // Initialize the update subscription status logic
        new Waka_Update_Subscription();

        //Initialize the stripe payment
        new Waka_Stripe_Payment();

        //new Waka_Mailer_Lite();
        // Enqueue styles
        add_action('wp_enqueue_scripts', [$this, 'enqueue_styles']);

        add_action('rest_api_init', function () {
            register_rest_route('waka-mailer-lite/v1', '/subscriber-id', array(
                'methods' => 'POST',
                'callback' => [$this, 'get_subscriber_id_by_email'],
                'permission_callback' => '__return_true', // Adjust permissions as necessary
            ));
        });

    }

    public function get_subscriber_id_by_email(WP_REST_Request $request)
    {
        // Get the email from the query parameters
        $email = $request->get_param('email');
        $name = $request->get_param('name');
        $groupId = $request->get_param('group');

        if (empty($email)) {
            return new WP_Error('no_email', 'No email provided', array('status' => 400));
        }

        // Create an instance of the Waka_Mailer_Lite class
        $mailerLite = new Waka_Mailer_Lite();

        // Call the method to get the subscriber ID
        $subscriberId = $mailerLite->handleSubscription($email, $name, $groupId);

        //Return the result as a JSON response
        return rest_ensure_response(json_decode($subscriberId, true));
    }

    public function enqueue_styles()
    {
        // Register and enqueue the CSS file
        wp_enqueue_style(
            'waka-premium-style', // Handle
            plugin_dir_url(__FILE__) . 'style.css', // Path to the CSS file
            [], // Dependencies (if any)
            '1.0', // Version
            'all' // Media type
        );
    }
}

// Initialize the plugin
if (class_exists('Waka_Premium')) {
    $waka_premium = new Waka_Premium();
}
