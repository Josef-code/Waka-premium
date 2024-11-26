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
