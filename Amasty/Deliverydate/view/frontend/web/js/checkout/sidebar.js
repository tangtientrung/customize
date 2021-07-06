define(
    [
        'jquery',
        'uiComponent',
        'Magento_Checkout/js/model/quote'
    ],
    function($, Component, quote) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'Amasty_Deliverydate/deliverydate-sidebar'
            },

            getDeliveryDate: function() {
               if (quote.amastyDeliveryDate && quote.amastyDeliveryDate.dateFormated) {
                   return quote.amastyDeliveryDate.dateFormated;
               }
                return '';
            },

            getDeliveryTime: function() {
               if (quote.amastyDeliveryDate && quote.amastyDeliveryDate.time) {
                   return quote.amastyDeliveryDate.time;
               }
                return '';
            },

            getDeliveryComment: function() {
               if (quote.amastyDeliveryDate && quote.amastyDeliveryDate.comment) {
                   return quote.amastyDeliveryDate.comment;
               }
                return '';
            },

            isModuleEnabled: function() {
                return window.checkoutConfig.amasty.deliverydate.moduleEnabled;
            }
        });
    }
);
