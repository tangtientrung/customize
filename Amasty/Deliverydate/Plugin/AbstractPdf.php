<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */


namespace Amasty\Deliverydate\Plugin;

/**
 * Insert Delivery Date information Block to PDF
 */
abstract class AbstractPdf
{
    /**
     * @var \Magento\Sales\Model\Order\Shipment[] | \Magento\Sales\Model\Order\Invoice[]
     */
    protected $objects = [];

    /**
     * @var \Amasty\Deliverydate\Model\DeliverydateRepository
     */
    protected $dateRepository;

    /**
     * @var \Amasty\Deliverydate\Helper\Data
     */
    protected $deliveryHelper;

    public function __construct(
        \Amasty\Deliverydate\Model\DeliverydateRepository $dateRepository,
        \Amasty\Deliverydate\Helper\Data $deliveryHelper
    ) {
        $this->dateRepository = $dateRepository;
        $this->deliveryHelper = $deliveryHelper;
    }

    /**
     * @param \Magento\Sales\Model\Order\Pdf\AbstractPdf $subject
     * @param \Magento\Sales\Model\Order\Shipment[] | \Magento\Sales\Model\Order\Invoice[] $objects
     *
     * @return array
     */
    public function beforeGetPdf($subject, $objects = [])
    {
        $this->objects = $objects;
        return [$objects];
    }

    /**
     * @param \Magento\Sales\Model\Order\Pdf\AbstractPdf $subject
     * @param \Zend_Pdf_Page $page
     * @param string $text
     *
     * @return array
     */
    public function beforeInsertDocumentNumber($subject, $page, $text)
    {
        $order = $this->getCurrentOrder($text);
        $fieldToShow = $this->getWhatShow();
        if (!$order || !count($fieldToShow)) {
            return [$page, $text];
        }
        $deliveryDate = $this->getCurrentDeliveryDate($order);
        if (!$deliveryDate) {
            return [$page, $text];
        }

        $this->drawDeliveryDateHeader($subject, $page);

        if ($deliveryDate->getDate() && in_array('date', $fieldToShow)) {
            $formattedDeliveryDate = $this->deliveryHelper->convertDateOutput($deliveryDate->getDate());
            $page->drawText(__('Delivery Date') . ': ' . $formattedDeliveryDate, 35, $subject->y, 'UTF-8');
            $subject->y -= 15;
        }
        if ($deliveryDate->getTime() && in_array('time', $fieldToShow)) {
            $page->drawText(__('Delivery Time') . ': ' . $deliveryDate->getTime(), 35, $subject->y, 'UTF-8');
            $subject->y -= 15;
        }
        if ($deliveryDate->getComment() && in_array('comment', $fieldToShow)) {
            $commentLines = explode("\n", $deliveryDate->getComment());
            $page->drawText(__('Delivery Comments') . ': ', 35, $subject->y, 'UTF-8');
            $subject->y -= 10;
            foreach ($commentLines as $comment) {
                $page->drawText(trim($comment), 45, $subject->y, 'UTF-8');
                $subject->y -= 10;
            }
            $subject->y -= 5;
        }
        return [$page, $text];
    }

    /**
     * Paste block title to PDF
     *
     * @param \Magento\Sales\Model\Order\Pdf\AbstractPdf $subject
     * @param \Zend_Pdf_Page $page
     */
    protected function drawDeliveryDateHeader($subject, $page)
    {
        $page->setFillColor(new \Zend_Pdf_Color_RGB(0.93, 0.92, 0.92));
        $page->setLineColor(new \Zend_Pdf_Color_GrayScale(0.5));
        $page->setLineWidth(0.5);
        $page->drawRectangle(25, $subject->y, 570, $subject->y - 15);
        $subject->y -= 10;
        $page->setFillColor(new \Zend_Pdf_Color_RGB(0, 0, 0));

        //columns headers
        $lines[0][] = ['text' => __('Delivery Information'), 'feed' => 35];

        $lineBlock = ['lines' => $lines, 'height' => 5];

        $subject->drawLineBlocks($page, [$lineBlock], ['table_header' => true]);
        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
        $subject->y -= 20;
    }

    /**
     * Get array of Delivery Date fields name which can be drawn in PDF
     *
     * @return string[]
     */
    abstract protected function getWhatShow();

    /**
     * Get order for current PDF page.
     * GetPdf method contains array of Shipment (or Invoice), in this method we search current Shipment (or Invoice)
     *
     * @param string $text
     *
     * @return \Magento\Sales\Model\Order|false
     */
    protected function getCurrentOrder($text)
    {
        if (!count($this->objects)) {
            return false;
        }
        // if we cant find which shipment (or Invoice) element on current page, then just take first.
        $currentObject = current($this->objects);
        foreach ($this->objects as $object) {
            if ($this->getPhrasePrefix() . $object->getIncrementId() == $text) {
                $currentObject = $object;
                break;
            }
        }

        return $currentObject->getOrder();
    }

    /**
     * Get Phrase prefix of page title. For find current shipment (or Invoice)
     *
     * @return \Magento\Framework\Phrase|string
     */
    abstract protected function getPhrasePrefix();

    /**
     * Get Delivery Date entity for current Order
     *
     * @param \Magento\Sales\Model\Order $order
     *
     * @return \Amasty\Deliverydate\Model\Deliverydate|false
     */
    protected function getCurrentDeliveryDate($order)
    {
        try {
            $deliveryDate = $this->dateRepository->getByOrder($order->getId());
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            return false;
        }

        return $deliveryDate;
    }
}
