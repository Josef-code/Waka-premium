<?php

use App\UserManagement;
/**
 * Plugin Name: Waka Premium
 * Plugin URI: https://wakaforafricans.com
 * Description: Connects Stripe with MailerLite and restricts post content for non-premium subscribers.
 * Version: 1.0.0
 * Author: Joseph
 * License: GPL2
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/app/UserManagement.php';

// use App\Payment\PaymentGateway;

// (new PaymentGatewayDemo);

// use APP\UserManagement;

(new UserManagement());


function my_plugin_enqueue_styles()
{
    // Check if we are on the 'register' page
    if (is_page('register')) {
        // Enqueue the custom CSS file
        wp_enqueue_style('my-plugin-custom-style', plugin_dir_url(__FILE__) . 'assets/css/register.css');
    }
}
add_action('wp_enqueue_scripts', 'my_plugin_enqueue_styles');