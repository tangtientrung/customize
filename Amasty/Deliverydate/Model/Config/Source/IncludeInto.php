<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */

namespace Amasty\Deliverydate\Model\Config\Source;

class IncludeInto implements \Magento\Framework\Option\ArrayInterface
{

    const ORDER_PRINT  = 'order_print';
    const ORDER_EMAIL = 'order_email';
    const INVOICE_EMAIL = 'invoice_email';
    const SHIPMENT_EMAIL = 'shipment_email';
    const INVOICE_PDF = 'invoice_pdf';
    const SHIPMENT_PDF = 'shipment_pdf';

    public function toOptionArray()
    {
        return array(
            array(
                'value' => self::ORDER_PRINT,
                'label' => __('Print Copy of Order Confirmation')
            ),
            array(
                'value' => self::ORDER_EMAIL,
                'label' => __('Order Confirmation E-mail')
            ),
            array(
                'value' => self::INVOICE_EMAIL,
                'label' => __('Invoice E-mail')
            ),
            array(
                'value' => self::SHIPMENT_EMAIL,
                'label' => __('Shipment E-mail')
            ),
            array(
                'value' => self::INVOICE_PDF,
                'label' => __('Invoice PDF')
            ),
            array(
                'value' => self::SHIPMENT_PDF,
                'label' => __('Shipment PDF (Packing Slip)')
            ),
        );
    }
}
