define([
    'jquery',
    'Amasty_Deliverydate/js/checkout/date'
], function (
    $,
    checkoutDate
) {
    'use strict';

    return checkoutDate.extend({
        initConfig: function (options) {
            this.dataScope = '';
            this._super();

            this.outputDateFormat = options.amdeliveryconf.outputDateFormat;
            this.inputDateFormat = options.amdeliveryconf.inputDateFormat;
            this.pickerDateTimeFormat = options.amdeliveryconf.pickerDateTimeFormat;
            this.amdeliveryconf = options.amdeliveryconf;
            this.pickerDefaultDateFormat = options.amdeliveryconf.inputDateFormat;

            return this;
        },

        initObservable: function () {
            this._super();

            this.value.subscribe(function (date) {
                $('[data-amdelivery-js="backend-date"]').val(date);
            });

            $('#amdeliverydate_date').on('change', function (event) {
                this.shiftedValue(event.target.value);
            }.bind(this));

            // drown UI functional
            return this;
        },

        setInitialValue: function () {
            // drown UI functional
            return this;
        },

        initSwitcher: function () {
            // drown UI functional
            return this;
        }
    });
});
