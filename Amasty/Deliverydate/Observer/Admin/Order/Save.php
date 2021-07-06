<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */


namespace Amasty\Deliverydate\Observer\Admin\Order;

use Amasty\Deliverydate\Model\ResourceModel\Deliverydate;
use Magento\Framework\Event\ObserverInterface;

class Save implements ObserverInterface
{

    /**
     * @var \Amasty\Deliverydate\Model\DeliverydateFactory
     */
    private $deliverydateFactory;

    /**
     * @var \Amasty\Deliverydate\Model\TintervalFactory
     */
    private $tintervalFactory;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;

    /**
     * @var Deliverydate
     */
    private $deliverydateResourceModel;

    /**
     * @var \Amasty\Deliverydate\Model\ResourceModel\Tinterval
     */
    private $tintervalResourceModel;

    /**
     * @var Deliverydate
     */
    private $deliverydateResource;

    public function __construct(
        \Amasty\Deliverydate\Model\DeliverydateFactory $deliverydateFactory,
        \Amasty\Deliverydate\Model\TintervalFactory $tintervalFactory,
        \Magento\Framework\App\RequestInterface $request,
        Deliverydate $deliverydateResourceModel,
        \Amasty\Deliverydate\Model\ResourceModel\Tinterval $tintervalResourceModel,
        \Amasty\Deliverydate\Model\ResourceModel\Deliverydate $deliverydateResource
    ) {
        $this->deliverydateFactory = $deliverydateFactory;
        $this->tintervalFactory = $tintervalFactory;
        $this->request = $request;
        $this->deliverydateResourceModel = $deliverydateResourceModel;
        $this->tintervalResourceModel = $tintervalResourceModel;
        $this->deliverydateResource = $deliverydateResource;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $observer->getOrder();

        $data = $this->request->getParam('amdeliverydate');
        if (is_array($data) && !empty($data)) {
            /** @var \Amasty\Deliverydate\Model\DeliveryDate $deliveryDate */
            $deliveryDate = $this->deliverydateFactory->create();
            if ($deliveryDate->prepareForSave($data, $order)) {
                $this->deliverydateResource->save($deliveryDate);
            }
        }
    }
}
