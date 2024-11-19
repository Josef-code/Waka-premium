<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Waka_Content_Restriction
{

    /**
     * Constructor to initialize the class and register the shortcode
     */
    public function __construct()
    {
        // Register the shortcode
        add_shortcode('restrict_content', array($this, 'restrict_content_by_subscription_status'));
    }

    /**
     * Restrict content by user's subscription status
     *
     * @param array  $atts Shortcode attributes
     * @param string $content Content inside the shortcode
     * @return string The content or an error message
     */
    public function restrict_content_by_subscription_status($atts, $content = null)
    {
        // Get the current user
        $current_user = wp_get_current_user();

        // Check if user is logged in
        if (is_user_logged_in()) {
            // Get the user's subscription status (assuming it's saved as user meta)
            $subscription_status = get_user_meta($current_user->ID, 'subscription_status', true);

            // Check if the subscription status is 'active'
            if ($subscription_status === 'active') {
                // Return the content if the user has an active subscription
                return $content;
            } else {
                // User doesn't have an active subscription
                return '<div class="content-restriction-notice">
                        <h2>This post is for paid members only</h2>
                        <p>Become a paid member for unlimited access to articles, bonus contents, newsletters and more.</p>
                        <a href="/pricing-page"><button>SUBSCRIBE</button></a>
                        <p>Already have an account? <a href="/login">Sign in</a></p>
                        </div>';
            }
        } else {
            // User is not logged in
            return '<div class="content-restriction-notice">
                        <h2>This post is for paid members only</h2>
                        <p>Become a paid member for unlimited access to articles, bonus contents, newsletters and more.</p>
                        <a href="/pricing-page"><button>SUBSCRIBE</button></a>
                        <p>Already have an account? <a href="/login">Sign in</a></p>
                        </div>';
        }
    }
}
