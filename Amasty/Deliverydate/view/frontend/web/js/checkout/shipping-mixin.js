define(
    [],
    function () {
        'use strict';

        return function (Shipping) {
            return Shipping.extend({
                validateShippingInformation: function () {
                    var validationResult = this._super();
                    var additionalFields = this.getChild('shippingAdditional');
                    if (additionalFields && additionalFields.hasChild('amasty-delivery-date')) {
                        return validationResult && additionalFields.getChild('amasty-delivery-date').validate()
                    }
                    return validationResult;
                }
            });
        }
    }
);
