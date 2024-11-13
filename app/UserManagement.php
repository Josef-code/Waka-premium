<?php

namespace App;

class UserManagement
{

    public static function redirectToStripe()
    {
        // This is where we will hook into the action.
        add_action('user_created_redirect', function () {
            // Replace 'https://your-external-url.com' with your desired URL
            wp_redirect('https://your-external-url.com');
            exit; // Always call exit after wp_redirect to prevent further execution
        });
    }



}

