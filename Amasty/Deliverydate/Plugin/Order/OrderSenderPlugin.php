<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */


namespace Amasty\Deliverydate\Plugin\Order;

use Magento\Sales\Model\Order\Email\Sender\OrderSender;
use Amasty\Deliverydate\Model\LoaderExtensions;
use Magento\Sales\Model\Order;

class OrderSenderPlugin
{
    /**
     * @var LoaderExtensions
     */
    private $loaderExtensions;

    public function __construct(
        LoaderExtensions $loaderExtensions
    ) {
        $this->loaderExtensions = $loaderExtensions;
    }

    /**
     * @param OrderSender $subject
     * @param Order $order
     * @param bool $forceSyncMode
     *
     * @return array
     */
    public function beforeSend(OrderSender $subject, Order $order, $forceSyncMode = false)
    {
        $this->loaderExtensions->loadDeliveryDateExtensionAttributes($order);

        return [$order, $forceSyncMode];
    }
}
