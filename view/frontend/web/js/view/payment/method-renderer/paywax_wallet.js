/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 * Copyright © 2020 Paywax SAS. All rights reserved.
 * License: OSL 3.0 https://opensource.org/licenses/OSL-3.0
 */
/*browser:true*/
/*global define*/
define(
    [
        'Magento_Checkout/js/view/payment/default'
    ],
    function (Component) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Paywax_Wallet/payment/form',
                transactionResult: '',
            },

            initObservable: function () {

                this._super()
                    .observe([
                        'transactionResult',
                    ]);
                return this;
            },

            getCode: function() {
                return 'paywax_wallet';
            },

            getData: function() {
                return {
                    'method': this.item.method,
                    'additional_data': {
                        'transaction_result': this.transactionResult(),
                    }
                };
            },

            getTransactionResults: function() {
                return _.map(window.checkoutConfig.payment.paywax_wallet.transactionResults, function(value, key) {
                    return {
                        'value': key,
                        'transaction_result': value
                    }
                });
            },
        });
    }
);