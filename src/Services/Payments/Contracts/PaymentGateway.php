<?php
/**
 * Created by PhpStorm.
 * User: Zorro
 * Date: 4/5/2016
 * Time: 12:51 PM
 */

namespace App\Services\Payments\Contracts;


use App\Services\Payments\Traits\PaymentResponseTrait;
use App\Services\Payments\Traits\UsesPayloadTrait;

abstract class PaymentGateway
{
    use UsesPayloadTrait, PaymentResponseTrait;




}