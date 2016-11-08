<?php
/**
 * Created by PhpStorm.
 * User: Zorro
 * Date: 4/3/2016
 * Time: 2:52 PM
 */
namespace App\Services\Payments;

use App\Exceptions\PaymentErrorException;
use App\User;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;


define("AUTHORIZENET_LOG_FILE", "phplog");

date_default_timezone_set('America/Los_Angeles');
class AuthorizeDotNet
{

    private $price;
    private $cardNumber;
    private $occurances = 9999;
    private $expirationDate;
    private $interval;
    private $refId;
    private $intervalType = 'days';
    private $subscriptionName;

    /**
     * @return mixed
     */
    public function getRefId()
    {
        return $this->refId;
    }

    /**
     * @param mixed $refId
     * @return AuthorizeDotNet
     */
    public function setRefId($refId)
    {
        $this->refId = $refId;
        return $this;
    }



    /**
     * @return string
     */
    public function getIntervalType()
    {
        return $this->intervalType;
    }

    /**
     * @param string $intervalType
     * @return AuthorizeDotNet
     */
    public function setIntervalType($intervalType)
    {
        $this->intervalType = $intervalType;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubscriptionName()
    {
        return $this->subscriptionName;
    }

    /**
     * @param mixed $subscriptionName
     * @return AuthorizeDotNet
     */
    public function setSubscriptionName($subscriptionName)
    {
        $this->subscriptionName = $subscriptionName;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getInterval()
    {
        return $this->interval;
    }

    /**
     * @param mixed $interval
     * @return AuthorizeDotNet
     */
    public function setInterval($interval)
    {
        $this->interval = $interval;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     * @return AuthorizeDotNet
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCardNumber()
    {
        return $this->cardNumber;
    }

    /**
     * @param mixed $cardNumber
     * @return AuthorizeDotNet
     */
    public function setCardNumber($cardNumber)
    {
        $this->cardNumber = $cardNumber;
        return $this;
    }

    /**
     * @return int
     */
    public function getOccurances()
    {
        return $this->occurances;
    }

    /**
     * @param int $occurances
     * @return AuthorizeDotNet
     */
    public function setOccurances($occurances)
    {
        $this->occurances = $occurances;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    /**
     * @param mixed $expirationDate
     * @return AuthorizeDotNet
     */
    public function setExpirationDate($expirationDate)
    {
        $this->expirationDate = $expirationDate;
        return $this;
    }


    public function createSubscription(User $user) {

        // Common Set Up for API Credentials
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(config('subscription.API_LOGIN_ID'));
        $merchantAuthentication->setTransactionKey(config('subscription.TRANSACTION_KEY'));

        $refId = $this->refId;

        // Subscription Type Info
        $subscription = new AnetAPI\ARBSubscriptionType();
        $subscription->setName($this->subscriptionName);

        $interval = new AnetAPI\PaymentScheduleType\IntervalAType();
        $interval->setLength($this->interval);
        $interval->setUnit($this->intervalType);

        $paymentSchedule = new AnetAPI\PaymentScheduleType();
        $paymentSchedule->setInterval($interval);
        $paymentSchedule->setStartDate(new \DateTime(date("Y-m-d")));
        $paymentSchedule->setTotalOccurrences("9999");
        /*$paymentSchedule->setTrialOccurrences("1");*/

        $subscription->setPaymentSchedule($paymentSchedule);
        $subscription->setAmount($this->price);
        /*$subscription->setTrialAmount("0.00");*/

        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($this->cardNumber);
        $creditCard->setExpirationDate($this->expirationDate);

        $payment = new AnetAPI\PaymentType();
        $payment->setCreditCard($creditCard);

        $subscription->setPayment($payment);

        $billTo = new AnetAPI\NameAndAddressType();
        $billTo->setFirstName($user->first_name);
        $billTo->setLastName($user->last_name);

        $subscription->setBillTo($billTo);

        $request = new AnetAPI\ARBCreateSubscriptionRequest();
        $request->setmerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setSubscription($subscription);
        $controller = new AnetController\ARBCreateSubscriptionController($request);

        $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);

        /*dd($response);*/
        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") )
        {
            return $response->getSubscriptionId();
        }
        else
        {
            throw new PaymentErrorException($response->getMessages()->getMessage()[0]->getText());
        }


    }


    public function getSubscriptionStatus($subscriptionId, $refId) {

        // Common Set Up for API Credentials
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(config('subscription.API_LOGIN_ID'));
        $merchantAuthentication->setTransactionKey(config('subscription.TRANSACTION_KEY'));

        $request = new AnetAPI\ARBGetSubscriptionStatusRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setSubscriptionId($subscriptionId);

        $controller = new AnetController\ARBGetSubscriptionStatusController($request);

        $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);

        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok"))
        {
            echo "SUCCESS: Subscription Status : " . $response->getStatus() . "\n";
        }
        else
        {
            echo "ERROR :  Invalid response\n";
            echo "Response : " . $response->getMessages()->getMessage()[0]->getCode() . "  " .$response->getMessages()->getMessage()[0]->getText() . "\n";

        }

        return $response;
    }

}
