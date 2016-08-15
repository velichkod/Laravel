<?php
/**
 * Created by PhpStorm.
 * User: Zorro
 * Date: 4/5/2016
 * Time: 3:01 PM
 */

namespace App\Services\Payments\Traits;


use app\Services\Payments\Contracts\Payload;

trait UsesPayloadTrait
{
    protected $payload;

    public function setPayload(Payload $payload)
    {
        $this->payload = $payload;
        return $this;
    }

    public function getPayload()
    {
        return $this->payload;
    }
}