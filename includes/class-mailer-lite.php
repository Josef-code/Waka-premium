<?php

if (!defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/../vendor/autoload.php';

use MailerLite\MailerLite;

class Waka_Mailer_Lite
{
    private $mailerLite;

    public function __construct()
    {
        $this->mailerLite = new MailerLite(['api_key' => '']);

    }

    public function handleSubscription($email, $name, $groupId)
    {
        // Check if the user is already subscribed
        $subscriberId = $this->getSubscriberIdByEmail($email);

        if (isset($subscriberId)) {
            // User is already subscribed

            $this->addSubscriber($email, $name = null, $groupId);
        } else {
            // User is not subscribed, add them
            $subscriberResponse = $this->addSubscriber($email, $name, $groupId);

            // // Check if the response is a string
            // if (is_string($subscriberResponse)) {
            //     // Decode the response
            //     $decodedResponse = json_decode($subscriberResponse, true);

            //     // Check if the body is set and return it
            //     if (isset($decodedResponse[0]['body'])) {
            //         return json_encode($decodedResponse[0]['body']);
            //     } else {
            //         return json_encode(["error" => "No body found in the response."]);
            //     }
            // } else {
            //     // If the response is already an array, return the body directly
            //     if (isset($subscriberResponse['body'])) {
            //         return json_encode($subscriberResponse['body']);
            //     } else {
            //         return json_encode(["error" => "No body found in the response."]);
            //     }
            // }
        }
    }

    public function getSubscriberIdByEmail($email)
    {
        $url = "https://connect.mailerlite.com/api/subscribers/{$email}";

        // Set up the request arguments
        $args = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . '' // Replace with your actual API key
            ],
        ];

        // Make the API call
        $response = wp_remote_get($url, $args);

        // Check for errors in the response
        if (is_wp_error($response)) {
            // Handle the error
            return null;
        }

        // Decode the response body
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        // Check if the subscriber exists and return the ID
        return isset($data['data']['id']) ? $data['data']['id'] : null; // Return the subscriber ID if found
    }

    public function addSubscriber($email, $name, $groupId)
    {
        $data = [
            'email' => $email,
            'fields' => [
                'name' => $name,
            ],
            'groups' => [
                $groupId,
            ]
        ];

        try {
            return $this->mailerLite->subscribers->create($data);
        } catch (Exception $e) {
            // Handle the exception 
            return ['error' => $e->getMessage()];
        }
    }

    public function removeSubscriber($groupId, $subscriberId)
    {
        $this->mailerLite->groups->unAssignSubscriber($groupId, $subscriberId);
    }

}