define([
    'jquery',
    'ko',
    'mage/url',
    'mage/storage',
    'Magento_Payment/js/view/payment/cc-form'
],
function ($, ko,urlBuilder, storage,Component) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'AHT_Payoo/payment/payoo'
        },
        
        isChecked: ko.observable(),
        checked: ko.observable(),
        getCode: ko.observable(),
        payoo: ko.observableArray([]),
        selectPaymentMethod :function()
        {
            var self = this;
            self.isChecked('payoo');
            var url = urlBuilder.build('payoo/index/getbankcode');
            storage.post(
                url,
                JSON.stringify(),
                false
            ).done(
                function (response) {
                    console.log(response);
                    // self.getCode = response.forEach(element => {
                    //     element.getCode() = ko.observable('PAYOO');
                    // });
                    // self.payoo = ko.observableArray($.map(response, function (element) {
                    //     return element;
                    // }));
                    // console.log(self.payoo);
                    var result = $.map(response, function (item) {
                        // var code = $.map(respone, function (item) {
                        //     return item;
                        // });
                        
                        return item;
                    });
                    self.payoo(result);
                    console.log(typeof(result));
                    console.log(result);
                    self.getCode = function() {
                        return 'payoo';
                    };
                }

            ).fail(
            );
        },
        context: function() {
            return this;
        },

        getCode: function() {
            return 'payoo';
        },

        isActive: function() {
            return true;
        },
        getTitle: function() {
            return "Payoo"
        }
    });
}
);
