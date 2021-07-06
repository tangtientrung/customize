<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */


namespace Amasty\Deliverydate\Model;

use Amasty\Deliverydate\Model\DeliverydateRepository;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Model\Order;

class LoaderExtensions
{
    /**
     * @var DeliverydateRepository
     */
    private $deliveryDateRepository;

    /**
     * @var OrderExtensionFactory
     */
    private $orderExtensionFactory;

    public function __construct(
        DeliverydateRepository $deliverydateRepository,
        OrderExtensionFactory $orderExtensionFactory
    ) {
        $this->deliveryDateRepository = $deliverydateRepository;
        $this->orderExtensionFactory = $orderExtensionFactory;
    }

    /**
     * @param Order $order
     */
    public function loadDeliveryDateExtensionAttributes(Order $order)
    {
        $extensionAttributes = $order->getExtensionAttributes();

        if ($extensionAttributes === null) {
            $extensionAttributes = $this->orderExtensionFactory->create();
        }
        if ($extensionAttributes->getAmdeliverydateDate() !== null) {
            // Delivery Date entity is already loaded; no actions required
            return;
        }
        try {
            $deliveryDate = $this->deliveryDateRepository->getByOrder($order->getEntityId());

            $extensionAttributes->setAmdeliverydateDate($deliveryDate->getDate());
            $extensionAttributes->setAmdeliverydateTime($deliveryDate->getTime());
            $extensionAttributes->setAmdeliverydateComment($deliveryDate->getComment());

            $order->setExtensionAttributes($extensionAttributes);
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            // Delivery Date entity cannot be loaded for current order; no actions required
            return;
        }
    }
}
