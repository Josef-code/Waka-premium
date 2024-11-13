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


