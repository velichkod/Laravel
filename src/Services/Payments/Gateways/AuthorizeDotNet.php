<?php
/**
 * Created by PhpStorm.
 * User: Zorro
 * Date: 4/5/2016
 * Time: 12:53 PM
 */

namespace App\Services\Payments\Gateways;


use App\Exceptions\PaymentErrorException;
use App\Services\Payments\Contracts\Gateway;
use App\Services\Payments\Contracts\PaymentGateway;
use Illuminate\Contracts\Auth\Access\Gate;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;


class AuthorizeDotNet extends PaymentGateway implements Gateway
{

    public function createSubscription()
    {
        // Common Set Up for API Credentials
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(config('subscription.API_LOGIN_ID'));
        $merchantAuthentication->setTransactionKey(config('subscription.TRANSACTION_KEY'));

        $refId = $this->getPayload()->refId;

        // Subscription Type Info
        $subscription = new AnetAPI\ARBSubscriptionType();
        $subscription->setName($this->getPayload()->subscriptionName);

        $interval = new AnetAPI\PaymentScheduleType\IntervalAType();
        $interval->setLength($this->getPayload()->interval);
        $interval->setUnit($this->getPayload()->intervalType);

        $paymentSchedule = new AnetAPI\PaymentScheduleType();
        $paymentSchedule->setInterval($interval);
        $paymentSchedule->setStartDate(new \DateTime($this->getPayload()->startDate));
        $paymentSchedule->setTotalOccurrences("9999");
        /*$paymentSchedule->setTrialOccurrences("1");*/

        $subscription->setPaymentSchedule($paymentSchedule);
        $subscription->setAmount($this->getPayload()->price);
        /*$subscription->setTrialAmount("0.00");*/

        $profile = new AnetAPI\CustomerProfileIdType();
        $profile->setCustomerProfileId($this->getPayload()->customer['ID']);
        $profile->setCustomerPaymentProfileId($this->getPayload()->customer['PAYMENT_ID']);
        /*$profile->setCustomerAddressId($customerAddressId);*/

        $subscription->setProfile($profile);

        $request = new AnetAPI\ARBCreateSubscriptionRequest();
        $request->setmerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setSubscription($subscription);
        $controller = new AnetController\ARBCreateSubscriptionController($request);


        $this->setResponse($controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX));

        /*var_dump($this->getResponse());*/
        /*dd($response);*/
        if (($this->getResponse() != null) && ($this->getResponse()->getMessages()->getResultCode() == "Ok")) {
            return $this->getResponse();
        } else {
            throw new PaymentErrorException($this->getResponse()->getMessages()->getMessage()[0]->getText());
        }
    }

    public function updateSubscription()
    {
        // TODO: Implement updateSubscription() method.
    }

    public function cancelSubscription()
    {
        // Common Set Up for API Credentials
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(config('subscription.API_LOGIN_ID'));
        $merchantAuthentication->setTransactionKey(config('subscription.TRANSACTION_KEY'));

        $request = new AnetAPI\ARBCancelSubscriptionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        /*$request->setRefId($refId);*/
        $request->setSubscriptionId($this->getPayload()->subscriptionId);

        $controller = new AnetController\ARBCancelSubscriptionController($request);

        $this->setResponse($controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX));

        if (($this->getResponse() != null) && ($this->getResponse()->getMessages()->getResultCode() == "Ok")) {
            return $this->getResponse();
        } else {
            throw new PaymentErrorException($this->getResponse()->getMessages()->getMessage()[0]->getText());
        }


    }
}