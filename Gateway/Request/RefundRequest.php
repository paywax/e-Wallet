<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 * Copyright © 2020 Paywax SAS. All rights reserved.
 * License: OSL 3.0 https://opensource.org/licenses/OSL-3.0
*/
namespace Paywax\Wallet\Gateway\Request;

use Magento\Payment\Gateway\ConfigInterface;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Magento\Store\Model\StoreManagerInterface;
use Paywax\Wallet\Gateway\Http\Client\PaywaxWalletClient;

class RefundRequest implements BuilderInterface
{
    const FORCE_RESULT = 'FORCE_RESULT';

    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config,StoreManagerInterface $storeManager)
    {
        $this->config = $config;
        $this->storeManager = $storeManager;
    }

    /**
     * Builds ENV request
     *
     * @param array $buildSubject
     * @return array
     */
    public function build(array $buildSubject)
    {
        if (!isset($buildSubject['payment'])
        || !$buildSubject['payment'] instanceof PaymentDataObjectInterface
        ) {
            throw new \InvalidArgumentException('Payment data object should be provided');
        }

        /** @var \Magento\Payment\Gateway\Data\PaymentDataObject $paymentDataObject */
        $paymentDataObject = \Magento\Payment\Gateway\Helper\SubjectReader::readPayment($buildSubject);
        $amount = \Magento\Payment\Gateway\Helper\SubjectReader::readAmount($buildSubject);

        $transactionResult = $paymentDataObject->getPayment()->getAdditionalInformation('paymentIntentId');
        $data = array(
            'paymentIntentId' =>  $transactionResult,
            'amount' =>  $amount * 100,
        );
        $client = new \Zend\Http\Client();
        $client->setUri('https://safeconnecty.com/refund');
        $client->setOptions(array(
            'maxredirects' => 0,
            'timeout'      => 30
        ));
        $httpHeaders = new \Zend\Http\Headers();
        $httpHeaders->addHeaders([
            'Content-Type' => 'application/json',
            'Origin' => $this->storeManager->getStore()->getBaseUrl()
        ]);
        $client->setHeaders($httpHeaders);
        $client->setRawBody(json_encode($data));
        $client->setEncType('application/json');
        $client->setMethod('POST');
        $response = $client->send();
        $json = json_decode($response->getBody(), true);
        $status = $json['status'];
        if($status != 'ok') {
            return [
                self::FORCE_RESULT =>  PaywaxWalletClient::FAILURE
            ];
        }else{
            return [
                self::FORCE_RESULT => PaywaxWalletClient::SUCCESS
            ];
        }
    }
}