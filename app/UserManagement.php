<?php

namespace App;

class UserManagement
{
    public function __construct()
    {
        //add_action('init', [$this, 'register_shortcode']);
        add_shortcode('custom_registration', [$this, 'register_shortcode']);
    }

    public function register_form($registration_errors = [])
    {
        ob_start();
        ?>
        <div class="reg-body">
            <div class="reg-container">
                <h1>Sign up</h1>
                <p>Create an account or <a href="#">Sign in</a></p>

                <?php if (!empty($registration_errors)): ?>
                    <div class="error-messages">
                        <?php foreach ($registration_errors as $error): ?>
                            <div class="error"><?php echo esc_html($error); ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>


                <form method="post">
                    <label for="email">Email address</label>
                    <input type="email" id="email" name="user_email">

                    <label for="username">Username</label>
                    <input type="text" id="username" name="username">

                    <label for="password">Password</label>
                    <div class="password-container">
                        <input type="password" id="password" name="user_password">
                    </div>

                    <div class="checkbox-container">
                        <input type="checkbox" id="marketing" name="marketing">
                        <label for="marketing">I want to receive emails with advertising, news, suggestions or marketing
                            promotions</label>
                    </div>

                    <button name="register" type="submit">Sign up</button>
                </form>
                <p class="terms">By signing up to create an account, you are accepting our terms of service and privacy policy
                </p>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function register_shortcode()
    {

        if (is_user_logged_in()) {
            '<p>You are already registered and logged in.</p>'; // Return instead of echo
            exit();
        }

        $registeration_errors = [];

        if (isset($_POST['register'])) {
            $registration_errors = $this->handle_form_submission();

        }

        // Output the registration form HTML
        return $this->register_form($registration_errors);

    }

    public function handle_form_submission()
    {

        $username = sanitize_user($_POST['username']);
        $user_email = sanitize_email($_POST['user_email']);
        $user_password = sanitize_text_field($_POST['user_password']);
        $country = sanitize_text_field($_POST['country']);
        $phone_number = sanitize_text_field($_POST['phone_number']);

        global $registration_errors;

        // Create a WP_Error object
        $registration_errors = new \WP_Error();

        if (empty($username) || empty($user_email) || empty($user_password)) {
            $registration_errors->add('field', 'All fields are required.');
        }

        if (!is_email($user_email)) {
            $registration_errors->add('email_invalid', 'Email is not valid.');
        }

        // Validate email and username
        if (username_exists($username)) {
            $registration_errors->add('username_exists', 'Username already exists.');
        }

        if (email_exists($user_email)) {
            $registration_errors->add('email_exists', 'Email is already registered.');
        }

        // If there are no errors, proceed with registration
        if (empty($registration_errors->get_error_messages())) {
            // Code to register the user goes here
            $user_id = wp_create_user($username, $user_password, $user_email);

            if (is_wp_error($user_id)) {
                $registration_errors->add('registeration_failed', 'Error: Unable to create user.');
            } else {
                echo '<p>Registration successful. A new user has been created.</p>';
            }

            // echo '<p>Registration successful. A new user has been created.</p>';
            // } else {
            //     // Display errors
            //     foreach ($registration_errors->get_error_messages() as $error) {
            //         echo '<div class="error">' . $error . '</div>';
            //     }

        }

        return $registration_errors->get_error_messages();
    }
}


// Create the new user
// $user_id = wp_create_user($username, $user_password, $user_email);

// if (is_wp_error($user_id)) {
//     echo '<p>Error: Unable to create user.</p>';
//     return;
// }

//Add user meta (country and phone number)
// update_user_meta($user_id, 'country', $country);
// update_user_meta($user_id, 'phone_number', $phone_number);

// echo '<p>Registration successful. A new user has been created.</p>';
