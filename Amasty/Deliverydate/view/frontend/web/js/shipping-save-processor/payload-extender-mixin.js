define([
    'underscore',
    'jquery',
    'uiRegistry',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote'
], function (_, $, registry, wrapper, quote) {
    'use strict';

    return function (payloadExtender) {
        return wrapper.wrap(payloadExtender, function (original, payload) {
            var payloadOriginal = original(payload),
                payloadWithDeliveryDateInfo = payloadOriginal;

                if (window.checkoutConfig.amasty.deliverydate) {
                    var amdeliverydateDate = $('[name="amdeliverydate_date"]');
                    var amdeliverydateTime = $('[name="amdeliverydate_time"]');
                    var amdeliverydateComment = $('[name="amdeliverydate_comment"]');
                    var isVisibleAmdeliverydateDate = amdeliverydateDate.is(':visible');
                    var isVisibleAmdeliverydateTime = amdeliverydateTime.is(':visible');
                    var isVisibleAmdeliverydateComment = amdeliverydateComment.is(':visible');

                    quote.amastyDeliveryDate = [];
                    quote.amastyDeliveryDate.date = isVisibleAmdeliverydateDate ?
                        (quote.amastyDeliveryDateDate || amdeliverydateDate.val()) : '';
                    quote.amastyDeliveryDate.dateFormated = isVisibleAmdeliverydateDate ? amdeliverydateDate.val() : '';
                    quote.amastyDeliveryDate.time = isVisibleAmdeliverydateTime ?
                        (amdeliverydateTime.val() ? amdeliverydateTime.find('option:selected').text() : '') : '';
                    quote.amastyDeliveryDate.comment = isVisibleAmdeliverydateComment ? amdeliverydateComment.val() : '';

                    var deliveryData = {
                        amdeliverydate_date: isVisibleAmdeliverydateDate ? quote.amastyDeliveryDate.date : '',
                        amdeliverydate_time: isVisibleAmdeliverydateTime ? amdeliverydateTime.val() : '',
                        amdeliverydate_comment: isVisibleAmdeliverydateComment ? quote.amastyDeliveryDate.comment : ''
                    };

                    if (_.isUndefined(payloadWithDeliveryDateInfo.addressInformation.extension_attributes)) {
                        payloadWithDeliveryDateInfo.addressInformation.extension_attributes = {};
                    }

                    if (deliveryData) {
                        _.extend(payloadWithDeliveryDateInfo.addressInformation.extension_attributes, deliveryData);
                    }
                }

            return payloadWithDeliveryDateInfo;
        });
    };
});
