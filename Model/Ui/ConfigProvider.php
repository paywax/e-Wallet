<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 * Copyright © 2020 Paywax SAS. All rights reserved.
 * License: OSL 3.0 https://opensource.org/licenses/OSL-3.0
 */
namespace Paywax\Wallet\Model\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;
use Paywax\Wallet\Gateway\Http\Client\PaywaxWalletClient;

/**
 * Class ConfigProvider
 */
class ConfigProvider implements ConfigProviderInterface
{
    const CODE = 'paywax_wallet';

    /**
     * Retrieve assoc array of checkout configuration
     *
     * @return array
     */
    public function getConfig()
    {
        return [
            'payment' => [
                self::CODE => [
                    'transactionResults' => [
                        PaywaxWalletClient::SUCCESS => __('Success'),
                        PaywaxWalletClient::FAILURE => __('Fraud')
                    ]
                ]
            ]
        ];
    }
}
