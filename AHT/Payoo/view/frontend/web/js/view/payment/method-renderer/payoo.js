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
        payoo: ko.observableArray([]),
        // selectPayoo :function()
        // {
        //     var self = this;
        //     self.isChecked('payoo');
        //     var url = urlBuilder.build('payoo/index/getbankcode');
        //     alert(url);
        //     storage.post(
        //         url,
        //         JSON.stringify(),
        //         false
        //     ).done(
        //         function (response) {
        //             console.log(response);
        //             // var test = response.forEach(element => {
        //             //     element.check = ko.observable();
        //             // });
                    
        //             var result = $.map(response, function (item) {
        //                 // var code = $.map(respone, function (item) {
        //                 //     return item;
        //                 // });
                        
        //                 return item;
        //             });
        //             self.payoo(result);
        //             console.log(typeof(result));
        //             console.log(result);
        //         }

        //     ).fail(
        //     );
        // },
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
