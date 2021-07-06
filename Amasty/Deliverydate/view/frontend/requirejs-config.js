var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/view/shipping': {
                'Amasty_Deliverydate/js/checkout/shipping-mixin': true
            },
            'Magento_Checkout/js/model/shipping-save-processor/payload-extender': {
                'Amasty_Deliverydate/js/shipping-save-processor/payload-extender-mixin': true
            }
        }
    }
};