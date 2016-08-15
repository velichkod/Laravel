<?php
/**
 * Created by PhpStorm.
 * User: Zorro
 * Date: 4/5/2016
 * Time: 12:47 PM
 */

namespace App\Services\Payments\Contracts;


interface Gateway
{

    public function setPayload(Payload $payload);

    public function createSubscription();

    public function updateSubscription();

    public function cancelSubscription();

}