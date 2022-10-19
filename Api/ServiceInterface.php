<?php
/**
 * Copyright © 2020 Paywax SAS. All rights reserved.
 * License: OSL 3.0 https://opensource.org/licenses/OSL-3.0
*/
namespace Paywax\Wallet\Api;
interface ServiceInterface
{
    /**
     * POST for Paywax Wallet api
     *
     * @param  mixed  $shippingAddress
     * @param  string $paymentIntentId
     * @param  string $shippingOptionId
     * @param  string $paymentMethod
     * @param  string $email
     * @param  string $shallCreate
     * @param  string $sid
     * @param  string $quoteId
     * @param  string $prefix
     * @return mixed  Json Object
     */
    public function orderPostMethod($shippingAddress,$paymentIntentId,$shippingOptionId, $paymentMethod, $email, $shallCreate, $sid ,$quoteId, $prefix);

    /**
     * POST for Paywax Wallet api
     *
     * @param  string $maskedQuoteId
     * @return mixed  Json Object
    */
    public function cartPostMethod($maskedQuoteId);

}
