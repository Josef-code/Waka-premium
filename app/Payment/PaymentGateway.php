<?php

namespace App\Payment;

abstract class PaymentGateway
{
    abstract public function handleWebhook();

    abstract public function getSubscribers();

    abstract public function createSubscription();

    abstract public function cancelSubscription($subscriptionId);

    abstract public function getSubscription($subscriptionId);
}
