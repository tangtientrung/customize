define([
    'moment',
    'ko',
    'underscore',
    'jquery',
    'mage/translate',
    'mage/calendar'
], function (moment, ko, _, $, $t) {
    'use strict';


    var defaults = {
        dateFormat: 'mm\/dd\/yyyy',
        showsTime: false,
        timeFormat: null,
        buttonImage: null,
        showOn: 'both',
        buttonImageOnly: false,
        buttonText: $t('Select Date'),
        currentText: $t('First Available Day')
    };
    var map = {
            'D': 'd',
            'M': 'm'
        };
    /**
     * Converts mage date format to a moment.js format.
     *
     * @param {String} mageFormat
     * @returns {String}
     */
    var normalizeDate = function (mageFormat) {
        var result = mageFormat;

        _.each(map, function (moment, mage) {
            result = result.replace(new RegExp(mage,'g'), moment);
        });

        return result;
    };


    ko.bindingHandlers.amastydatepicker = {

        init: function (el, valueAccessor) {

            var config = valueAccessor(),
                observable,
                options = {};

            _.extend(options, defaults);

            if (typeof config === 'object') {
                observable = config.storage;

                _.extend(options, config.options);
            } else {
                observable = config;
            }
            var format = options.dateFormat;
            /*
             * Prepare format for calendar lib.
             * notice: it is not last prepare. in calendar.js short year pattern will be transfered to long.
             *      Always long year format on frontend.
             */
            options.dateFormat = normalizeDate(options.dateFormat);

            var date = moment(observable(), config.elem.pickerDateTimeFormat);

            // initialize datepicker
            $(el).calendar(options);
            // set initial calendar value (default)
            observable() && $(el).datepicker('setDate', date.format(config.elem.outputDateFormat));
            $(el).blur();

            ko.utils.registerEventHandler(el, 'change', function () {
                observable(this.value);
                if (this.value !== observable()) {
                    var date = moment(observable(), config.elem.pickerDateTimeFormat);
                    $(this).datepicker('setDate', date.format(config.elem.outputDateFormat));
                }
            });
        }
    };
});
