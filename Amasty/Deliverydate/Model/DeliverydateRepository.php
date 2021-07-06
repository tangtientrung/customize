<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */

namespace Amasty\Deliverydate\Model;

use Magento\Framework\Exception\NoSuchEntityException;

class DeliverydateRepository
{
    /**
     * @var ResourceModel\Deliverydate
     */
    private $deliverydateResource;

    /**
     * @var DeliverydateFactory
     */
    private $deliverydateFactory;

    /**
     * @var array
     */
    protected $modelsByOrder = [];

    /**
     * DeliverydateRepository constructor.
     *
     * @param ResourceModel\Deliverydate $deliverydateResource
     * @param DeliverydateFactory        $deliverydateFactory
     */
    public function __construct(
        \Amasty\Deliverydate\Model\ResourceModel\Deliverydate $deliverydateResource,
        \Amasty\Deliverydate\Model\DeliverydateFactory $deliverydateFactory
    ) {
        $this->deliverydateResource = $deliverydateResource;
        $this->deliverydateFactory = $deliverydateFactory;
    }

    /**
     * @param int $orderId
     *
     * @return Deliverydate
     * @throws NoSuchEntityException
     */
    public function getByOrder($orderId)
    {
        if (!isset($this->modelsByOrder[$orderId])) {
            /** @var Deliverydate $model */
            $model = $this->deliverydateFactory->create();
            $this->deliverydateResource->load($model, $orderId, 'order_id');
            if (!$model->getId()) {
                throw new NoSuchEntityException(__('Delivery Date for specified order ID "%1" not found.', $orderId));
            }
            $this->modelsByOrder[$orderId] = $model;
        }
        return $this->modelsByOrder[$orderId];
    }

    /**
     * @param Deliverydate $deliverydate
     *
     * @return Deliverydate
     */
    public function save($deliverydate)
    {
        $this->deliverydateResource->save($deliverydate);
        return $deliverydate;
    }
}
