define([
    'uiComponent',
    'Magento_Checkout/js/model/payment/renderer-list'
],
function (Component, rendererList) {
    'use strict';

    rendererList.push(
        {
            type: 'payoo',
            component: 'AHT_Payoo/js/view/payment/method-renderer/payoo'
        }
    );

    /** Add view logic here if needed */
    return Component.extend({});
});