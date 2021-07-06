<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */


namespace Amasty\Deliverydate\Plugin\Order;

use Amasty\Deliverydate\Model\Config\Source\IncludeInto;

/**
 * Insert Delivery Date information Block to Shipment Admin PDF
 */
class ShipmentPdf extends \Amasty\Deliverydate\Plugin\AbstractPdf
{
    /**
     * @param \Magento\Sales\Model\Order\Pdf\Shipment $subject
     * @param \Magento\Sales\Model\Order\Shipment[]   $shipments
     *
     * @return array
     */
    public function beforeGetPdf($subject, $shipments = [])
    {
        // just for note. All code content in abstract.
        return parent::beforeGetPdf($subject, $shipments);
    }

    /**
     * @param \Magento\Sales\Model\Order\Pdf\Shipment $subject
     * @param \Zend_Pdf_Page                          $page
     * @param string                                  $text
     *
     * @return array
     */
    public function beforeInsertDocumentNumber($subject, $page, $text)
    {
        // just for note. All code content in abstract
        return parent::beforeInsertDocumentNumber($subject, $page, $text);
    }

    protected function getWhatShow()
    {
        return $this->deliveryHelper->whatShow(IncludeInto::SHIPMENT_PDF, 'include');
    }

    /**
     * {@inheritdoc}
     */
    protected function getPhrasePrefix()
    {
        return __('Packing Slip # ');
    }
}
