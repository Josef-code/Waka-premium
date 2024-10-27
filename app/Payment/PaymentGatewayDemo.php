<?php

namespace App\Payment;

class PaymentGatewayDemo
{
    public function __construct()
    {

        add_action('show_user_profile', [$this, 'custom_user_meta_field']);
        add_action('edit_user_profile', [$this, 'custom_user_meta_field']);
    }
    public function custom_user_meta_field()
    {
        ?>
        <h3><?php _e('Custom Field', 'text-domain'); ?></h3>

        <table class="form-table">
            <tr>
                <th><label for="custom_field"><?php _e('Custom Field', 'text-domain'); ?></label></th>
                <td>
                    <input type="text" name="custom_field" id="custom_field"
                        value="<?php echo esc_attr(get_user_meta(get_current_user_id(), 'custom_field', true)); ?>"
                        class="regular-text" />
                </td>
            </tr>
        </table>
        <?php

    }


}
