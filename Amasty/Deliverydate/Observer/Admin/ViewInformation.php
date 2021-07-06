<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */


namespace Amasty\Deliverydate\Observer\Admin;

use Magento\Framework\Event\ObserverInterface;

class ViewInformation implements ObserverInterface
{
    /**
     * @var \Amasty\Deliverydate\Helper\Data
     */
    protected $deliveryHelper;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    public function __construct(
        \Amasty\Deliverydate\Helper\Data $deliveryHelper,
        \Magento\Framework\Registry $coreRegistry
    ) {
        $this->deliveryHelper = $deliveryHelper;
        $this->coreRegistry = $coreRegistry;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->deliveryHelper->moduleEnabled()) {
            $elementName = $observer->getElementName();
            $transport = $observer->getTransport();
            $html = $transport->getOutput();
            $block = $observer->getLayout()->getBlock($elementName);

            if ('order_info' == $elementName) {
                $insert = $observer->getLayout()
                    ->createBlock('Amasty\Deliverydate\Block\Adminhtml\Sales\Order\View\Deliverydate');

                if ($block->getParentBlock() instanceof \Magento\Sales\Block\Adminhtml\Order\View\Tab\Info) {
                    $deliveryDate = $this->deliveryHelper->whatShow('order_view');
                    $html = $this->addToHtml($deliveryDate, $html, $insert);

                } elseif ($block->getParentBlock() instanceof \Magento\Sales\Block\Adminhtml\Order\Invoice\View\Form) {
                    $deliveryDate = $this->deliveryHelper->whatShow('invoice_view');
                    $this->coreRegistry->register('current_deliverydate_place', 'invoice');
                    $html = $this->addToHtml($deliveryDate, $html, $insert);

                } elseif ($block->getParentBlock() instanceof \Magento\Shipping\Block\Adminhtml\View\Form) {
                    $deliveryDate = $this->deliveryHelper->whatShow('shipment_view');
                    $this->coreRegistry->register('current_deliverydate_place', 'shipment');
                    $html = $this->addToHtml($deliveryDate, $html, $insert);
                }

            } elseif ('shipping_method' == $elementName) {
                if ($block->getParentBlock() instanceof \Magento\Sales\Block\Adminhtml\Order\Create\Data
                    || $block->getParentBlock() instanceof \Magento\Sales\Block\Adminhtml\Order\Create\Load
                ) {
                    $deliveryDate = $this->deliveryHelper->whatShow('order_create');
                    $insert = $observer->getLayout()
                        ->createBlock('Amasty\Deliverydate\Block\Adminhtml\Sales\Order\Create\Deliverydate');

                    $html = $this->addToHtml($deliveryDate, $html, $insert);
                }

            }

            $transport->setOutput($html);
        }
    }

    protected function addToHtml($deliveryDate, $html, $insert, $where = 'view')
    {
        if (!empty($deliveryDate)
            && false === strpos($html, 'BEGIN `Amasty: Delivery Date`')
        ) {
            $html .= $insert->toHtml();
        }

        return $html;
    }
}
