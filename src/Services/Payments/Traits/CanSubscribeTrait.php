<?php
namespace App\Services\Payments\Traits;


use App\Modules\Subscriptions\Plan;
use App\Modules\Users\UserSubscription;
use App\Services\Payments\Contracts\Payload;
use App\Services\Payments\Customers\AuthorizeDotNetCustomer;

trait CanSubscribeTrait
{
    private $tmpCardData;

    public function subscription()
    {
        return $this->hasOne('App\Modules\Users\UserSubscription', 'user_id');
    }

    public function subscribe(Plan $plan, \Closure $c = null)
    {
        /*$subscription = new UserSubscription(array('user_id' => $this->id));*/
        $this->subscription()->save(new UserSubscription());

        if(!is_null($c) && is_callable($c)){
            $c($this);
        }

        return $this->subscription->start($plan);
    }

    public function getPaymentCustomer()
    {
        if ($this->hasPaymentCustomer()) {
            return array(
                "ID" => $this->subscription_customer_id,
                "PAYMENT_ID" => $this->subscription_customer_payment_id,
                "SHIPPING_ID" => $this->subscription_customer_shipping_id
            );
        } else {
            $this->createSubscriptionCustomer();
            return $this->getPaymentCustomer();
        }
    }

    public function hasPaymentCustomer()
    {
        return $this->subscription_customer_payment_id != 0 || $this->subscription_customer_payment_id != '';
    }


    public function createSubscriptionCustomer()
    {
        $payload = Payload::Create(array_merge($this->getUserData(), $this->getCreditCardData()));
        $customer = AuthorizeDotNetCustomer::CreateCustomerProfile($payload);
        $this->saveCustomer($customer);

    }


    public function getUserData()
    {
        return array(
            'firstName' => $this->first_name,
            'lastName' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'zip' => $this->zip,
            'description' => 'HSMA Client'
        );
    }


    public function getCreditCardData()
    {
        return $this->tmpCardData;
    }

    public function setCardDataTmp($data)
    {
        $this->tmpCardData = $data;
        return $this;
    }

    public function saveCustomer($customer)
    {
        $this->subscription_customer_id = $customer->getCustomerProfileId();

        $paymentProfiles = $customer->getCustomerPaymentProfileIdList();
        $this->subscription_customer_payment_id = !is_null($paymentProfiles) ? end($paymentProfiles) : '';

        $shippingProfiles = $customer->getCustomerShippingAddressIdList();
        $this->subscription_customer_shipping_id = !is_null($shippingProfiles) ? end($shippingProfiles): '';

        $this->save();
    }

}