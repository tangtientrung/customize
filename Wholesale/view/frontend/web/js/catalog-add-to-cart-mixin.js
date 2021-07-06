define([
    'jquery',
    'mage/translate',
    'jquery/ui'
],
function ($, $t) {
    'use strict';

    return function (target) {
        $.widget('mage.catalogAddToCart', target, {
            options: {
                addToCartButtonTextWhileAdding: $t('Sending...'),
                addToCartButtonTextAdded: $t('Send request'),
                addToCartButtonTextDefault: $t('Sent request')
            }
        });

        return $.mage.catalogAddToCart;
    };
});