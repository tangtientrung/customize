<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */


namespace Amasty\Deliverydate\Observer\Order\Place;

use Magento\Framework\Event\ObserverInterface;

class Before implements ObserverInterface
{
    /**
     * @var \Amasty\Deliverydate\Helper\Data
     */
    private $amHelper;

    /**
     * @var \Amasty\Deliverydate\Model\DeliverydateFactory
     */
    private $deliverydateFactory;

    public function __construct(
        \Amasty\Deliverydate\Helper\Data $amHelper,
        \Amasty\Deliverydate\Model\DeliverydateFactory $deliverydateFactory
    ) {
        $this->amHelper = $amHelper;
        $this->deliverydateFactory = $deliverydateFactory;
    }


    /**
     * @param \Magento\Framework\Event\Observer $observer
     *
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $data = $this->amHelper->getDeliveryDataFromSession();
        if (is_array($data)) {
            $order = $observer->getOrder();
            /** @var \Amasty\Deliverydate\Model\Deliverydate $deliveryDate */
            $deliveryDate = $this->deliverydateFactory->create();
            $deliveryDate->prepareForSave($data, $order);
            $deliveryDate->validate($order);
        }

        return $this;
    }
}