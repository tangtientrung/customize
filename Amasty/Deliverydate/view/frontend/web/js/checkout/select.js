define([
        'ko',
        'jquery',
        'Magento_Ui/js/form/element/select'
    ], function (
    ko,
    $,
    AbstractField
    ) {
        'use strict';

        return AbstractField.extend({
            defaults: {
                deliverydateConfig: window.checkoutConfig.amasty.deliverydate,
                elementTmpl: 'Amasty_Deliverydate/form/element/select'
            },
            initConfig: function (config) {
                this._super();

                this.abridgeIntervalsSet = config.options.abridge == undefined ? config.options[1] : config.options.abridge;
                this.fullIntervalsSet = config.options.full == undefined ? config.options[0] : config.options.full;
                this.displayed = this.fullIntervalsSet;
            },
            onUpdate: function () {
                this.bubble('update', this.hasChanged());
                this.validate();
            },

            initObservable: function () {
                this._super();

                var newOptions = [];
                $.each(this.displayed, function (index, value) {
                    newOptions.push({
                        value: value.value,
                        label: value.label,
                        disabled: value.disabled
                    });
                });

                this.options(newOptions);

                return this;
            }

        });
    }
);