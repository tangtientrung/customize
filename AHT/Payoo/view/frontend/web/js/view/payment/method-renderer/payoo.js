define([
    'jquery',
    'Magento_Payment/js/view/payment/cc-form'
],
function ($, Component) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'AHT_Payoo/payment/payoo'
        },

        context: function() {
            return this;
        },

        getCode: function() {
            return 'payoo';
        },

        isActive: function() {
            return true;
        }
    });
}
);
