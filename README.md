# Waka Premium

**Waka Premium** is a WordPress plugin that provides seamless integration with Stripe to handle subscription payments, manage webhook confirmations, and automate user management in your newsletter lists based on Stripe events. It includes custom hooks to connect to Stripe, confirm webhooks and payments, and add/remove users from your newsletter list as needed.

## Features

- **Stripe Subscription Integration**: Connects to Stripe to collect subscription payments with the help of custom hooks.
- **Webhook Confirmation**: Automatically handles webhook events from Stripe to confirm payments and subscription statuses.
- **Newsletter List Management**: Adds or removes users from newsletter lists based on Stripe events (e.g., successful payments, cancellations, etc.).

## Installation

1. Download the `waka-premium` plugin from the GitHub repository.
2. Go to the **Plugins** section in your WordPress admin dashboard.
3. Click **Add New**, then **Upload Plugin**.
4. Select the downloaded plugin zip file and click **Install Now**.
5. Activate the plugin from the **Plugins** menu.

## Configuration

Once activated, you will need to configure the plugin to connect it to your Stripe account and set up the webhook handling for payments and subscription events.

### Webhooks
1. In the plugin settings, specify the URL where Stripe can send webhook events.
2. Go to your Stripe dashboard, and navigate to **Developers** > **Webhooks**.
3. Add the webhook URL from your plugin settings to Stripe.
4. Select the relevant events to send (e.g., `invoice.paid`, `invoice.payment_failed`).


## Usage

Once the plugin is set up and configured, it will automatically handle the following:

- **Subscription Payments**: When a user subscribes or renews their subscription, the plugin will handle the payment processing through Stripe.
- **Webhook Confirmation**: The plugin listens for and confirms webhook events from Stripe, ensuring payment success or failure is accurately tracked.
- **Newsletter Management**: The plugin listens for specific Stripe events (like successful payments or cancellations) and automatically adds or removes users from your newsletter list as needed.

## Example Scenario

1. A user subscribes to your premium service.
2. The plugin collects payment via Stripe.
3. Stripe sends a webhook confirming payment success.
4. The plugin adds the user to your newsletter list.
5. If the user cancels their subscription, the plugin removes them from your newsletter list.

## Requirements

- WordPress 6.0 or higher
- PHP 8.1 or higher
- Stripe Account (API Keys)
- Newsletter service (e.g., Mailchimp, ConvertKit)

## Support

For any issues or feature requests, please open an issue.

## License

This plugin is licensed under the MIT License. See the [LICENSE](LICENSE) file for more details.
