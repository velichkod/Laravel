<?php
/**
 * Created by PhpStorm.
 * User: Zorro
 * Date: 4/5/2016
 * Time: 2:32 PM
 */

namespace App\Services\Payments\Customers;

use App\Exceptions\PaymentErrorException;
use app\Services\Payments\Contracts\Payload;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
class AuthorizeDotNetCustomer
{
    public static function CreateCustomerProfile(Payload $payload)
    {
        // Common setup for API credentials

        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(config('subscription.API_LOGIN_ID'));
        $merchantAuthentication->setTransactionKey(config('subscription.TRANSACTION_KEY'));
        $refId = 'ref' . time();

        // Create the payment data for a credit card
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($payload->cardNumber);
        $creditCard->setExpirationDate($payload->expiryDate);
        $paymentCreditCard = new AnetAPI\PaymentType();
        $paymentCreditCard->setCreditCard($creditCard);

        // Create the Bill To info
        $billto = new AnetAPI\CustomerAddressType();
        $billto->setFirstName($payload->firstName);
        $billto->setLastName($payload->lastName);
        $billto->setCompany($payload->company);
        $billto->setAddress($payload->address);
        $billto->setCity($payload->city);
        $billto->setState($payload->state);
        $billto->setZip($payload->zip);
        $billto->setCountry("USA");

        // Create a Customer Profile Request
        //  1. create a Payment Profile
        //  2. create a Customer Profile
        //  3. Submit a CreateCustomerProfile Request
        //  4. Validate Profiiel ID returned

        $paymentprofile = new AnetAPI\CustomerPaymentProfileType();

        $paymentprofile->setCustomerType('individual');
        $paymentprofile->setBillTo($billto);
        $paymentprofile->setPayment($paymentCreditCard);
        $paymentprofiles[] = $paymentprofile;
        $customerprofile = new AnetAPI\CustomerProfileType();
        $customerprofile->setDescription($payload->description);
        $customerprofile->addToShipToList($billto);
        $merchantCustomerId = time() . rand(1, 150);
        $customerprofile->setMerchantCustomerId($merchantCustomerId);
        $customerprofile->setEmail($payload->email);
        $customerprofile->setPaymentProfiles($paymentprofiles);

        $request = new AnetAPI\CreateCustomerProfileRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setProfile($customerprofile);
        $controller = new AnetController\CreateCustomerProfileController($request);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
            return $response;
        } else {
            throw new PaymentErrorException($response->getMessages()->getMessage()[0]->getText());
        }
    }
}