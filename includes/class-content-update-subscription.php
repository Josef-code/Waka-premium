<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Waka_Update_Subscription
{

    /**
     * Constructor to initialize the class and register the shortcode
     */
    public function __construct()
    {
        // Register the hooks
        add_action('show_user_profile', array($this, 'add_subscription_status_field'));
        add_action('edit_user_profile', array($this, 'add_subscription_status_field'));
        add_action('personal_options_update', array($this, 'save_subscription_status_field'));
        add_action('edit_user_profile_update', array($this, 'save_subscription_status_field'));
    }

    /**
     * Update user's subscription status
     */
    public function add_subscription_status_field($user)
    {

        // Get the current value of subscription_status
        $subscription_status = get_user_meta($user->ID, 'subscription_status', true);
        ?>
        <h3><?php _e("Subscription Status", "wakaforafricans"); ?></h3>
        <table class="form-table">
            <tr>
                <th><label for="subscription_status"><?php _e("Subscription Status"); ?></label></th>
                <td>
                    <select name="subscription_status" id="subscription_status">
                        <option value="active" <?php selected($subscription_status, 'active'); ?>><?php _e('Active'); ?>
                        </option>
                        <option value="inactive" <?php selected($subscription_status, 'inactive'); ?>><?php _e('Inactive'); ?>
                        </option>
                    </select>
                </td>
            </tr>
        </table>
        <?php
    }

    public function save_subscription_status_field($user_id)
    {
        // Check if the current user has permission to edit users
        if (!current_user_can('edit_user', $user_id)) {
            return false;
        }


        // Update the subscription_status user meta
        update_user_meta($user_id, 'subscription_status', $_POST['subscription_status']);

    }
}
