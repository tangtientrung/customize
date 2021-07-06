<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */


namespace Amasty\Deliverydate\Plugin\Order;

use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

class OrderSave
{
    /**
     * @var \Amasty\Deliverydate\Helper\Data
     */
    private $amHelper;

    /**
     * @var \Amasty\Deliverydate\Model\DeliverydateFactory
     */
    private $deliverydateFactory;

    /**
     * @var \Amasty\Deliverydate\Model\ResourceModel\Deliverydate
     */
    private $deliverydateResource;

    public function __construct(
        \Amasty\Deliverydate\Helper\Data $amHelper,
        \Amasty\Deliverydate\Model\DeliverydateFactory $deliverydateFactory,
        \Amasty\Deliverydate\Model\ResourceModel\Deliverydate $deliverydateResource
    ) {
        $this->amHelper = $amHelper;
        $this->deliverydateFactory = $deliverydateFactory;
        $this->deliverydateResource = $deliverydateResource;
    }

    /**
     * Validate Order Delivery Date before place order
     *
     * @param OrderRepositoryInterface $subject
     * @param OrderInterface      $order
     *
     * @return OrderInterface
     */
    public function beforeSave(OrderRepositoryInterface $subject, OrderInterface $order)
    {
        $data = $this->amHelper->getDeliveryDataFromSession();
        if (is_array($data)) {
            /** @var \Amasty\Deliverydate\Model\Deliverydate $deliveryDate */
            $deliveryDate = $this->deliverydateFactory->create();
            $deliveryDate->prepareForSave($data, $order);
            $deliveryDate->validate($order);
        }

        return [$order];
    }

    /**
     * Save Order Delivery Date from session
     *
     * @param OrderRepositoryInterface $subject
     * @param OrderInterface      $order
     *
     * @return OrderInterface
     */
    public function afterSave(OrderRepositoryInterface $subject, OrderInterface $order)
    {
        $data = $this->amHelper->getDeliveryDataFromSession();
        if (is_array($data)) {
            /** @var \Amasty\Deliverydate\Model\Deliverydate $deliveryDate */
            $deliveryDate = $this->deliverydateFactory->create();
            if ($deliveryDate->prepareForSave($data, $order)) {
                $this->deliverydateResource->save($deliveryDate);
            }
        }
        return $order;
    }
}
