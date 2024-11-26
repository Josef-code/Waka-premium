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
        $this->mailerLite = new MailerLite(['api_key' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI0IiwianRpIjoiNTZjNzllZmE5OTE1MjA4YzA3YmRjYzUyZWM2M2RmMmZlZjg4MzU4NmIxM2QwYzBlNWVhMjY2ZDk5NTY2YTViMWFkMWQxNjkzYjUyMTU1MjMiLCJpYXQiOjE3MjY3NDc2ODkuNzA3NjY3LCJuYmYiOjE3MjY3NDc2ODkuNzA3NjcsImV4cCI6NDg4MjQyMTI4OS43MDI0MjgsInN1YiI6IjExMTc4NDciLCJzY29wZXMiOltdfQ.M6ZZmThzgBkmF2GV-5wIgp46hxe6N-ATiDN_z7Zy99g5Z0Rpcdbf6olZFS1oXvHrRJM0jPI8PgTYNi7jZQ4U9rTwtvmkPBmlwjxMfXRSowE_0Ai9MPJFj3o1tJ5IBCur_hZcD2acLH8uCzWvyV-xYvveccc21wtuVyZltl1K0YLElb8RdyWfvRtBjymMI6CKnjrHoWfuGnnCbZF8yqrOxKiOOBegvxcq_w3OJObre-ifGRx21qMaQPyVY-bvnymxhDALNeOcNEU5k-NmRUq0Ws4i0viT3Pi9vkb3rJ829Pn0mju-yk35PxSL_OJRdCKbXusO2et95dOzV_5PTK37RN6Xpot3sz9ww5Jyrpqgox0ytUKxqzN7ZzwLtyMEBKexLbgsa5BhGsQHUHkIljTIG63qp5m9FqdCs4oJPryrJZebheWTelXC44tGITRgztL0Gp1XR6lDnnqunRXGs30Ytl9L7tPKUqm-j_qG93PWbcYnJzraxYiqf5iJrqpI3Y9PLJ1HIADDlOvDjsJ4LSH0H4u8WNfa2TIv3f7qv1g1m9yhOihRmqKjdiiAKIKqTYTUXg45tBMsGaj4Eq4ZGtCMgT5SPkRI9YRvgN4zUFwigCaIPI-sx7JmDtWpdCF79lF8VciIPMaAG4Jswe2SdB8O5tfgaP2SkuQRb9skFv9IJH0']);

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
                'Authorization' => 'Bearer ' . 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI0IiwianRpIjoiNTZjNzllZmE5OTE1MjA4YzA3YmRjYzUyZWM2M2RmMmZlZjg4MzU4NmIxM2QwYzBlNWVhMjY2ZDk5NTY2YTViMWFkMWQxNjkzYjUyMTU1MjMiLCJpYXQiOjE3MjY3NDc2ODkuNzA3NjY3LCJuYmYiOjE3MjY3NDc2ODkuNzA3NjcsImV4cCI6NDg4MjQyMTI4OS43MDI0MjgsInN1YiI6IjExMTc4NDciLCJzY29wZXMiOltdfQ.M6ZZmThzgBkmF2GV-5wIgp46hxe6N-ATiDN_z7Zy99g5Z0Rpcdbf6olZFS1oXvHrRJM0jPI8PgTYNi7jZQ4U9rTwtvmkPBmlwjxMfXRSowE_0Ai9MPJFj3o1tJ5IBCur_hZcD2acLH8uCzWvyV-xYvveccc21wtuVyZltl1K0YLElb8RdyWfvRtBjymMI6CKnjrHoWfuGnnCbZF8yqrOxKiOOBegvxcq_w3OJObre-ifGRx21qMaQPyVY-bvnymxhDALNeOcNEU5k-NmRUq0Ws4i0viT3Pi9vkb3rJ829Pn0mju-yk35PxSL_OJRdCKbXusO2et95dOzV_5PTK37RN6Xpot3sz9ww5Jyrpqgox0ytUKxqzN7ZzwLtyMEBKexLbgsa5BhGsQHUHkIljTIG63qp5m9FqdCs4oJPryrJZebheWTelXC44tGITRgztL0Gp1XR6lDnnqunRXGs30Ytl9L7tPKUqm-j_qG93PWbcYnJzraxYiqf5iJrqpI3Y9PLJ1HIADDlOvDjsJ4LSH0H4u8WNfa2TIv3f7qv1g1m9yhOihRmqKjdiiAKIKqTYTUXg45tBMsGaj4Eq4ZGtCMgT5SPkRI9YRvgN4zUFwigCaIPI-sx7JmDtWpdCF79lF8VciIPMaAG4Jswe2SdB8O5tfgaP2SkuQRb9skFv9IJH0' // Replace with your actual API key
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