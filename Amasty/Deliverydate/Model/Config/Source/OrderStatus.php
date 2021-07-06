<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */

/**
 * Order Statuses source model
 */
namespace Amasty\Deliverydate\Model\Config\Source;

class OrderStatus implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var \Magento\Sales\Model\Order\Config
     */
    protected $orderConfig;

    /**
     * @param \Magento\Sales\Model\Order\Config $orderConfig
     */
    public function __construct(\Magento\Sales\Model\Order\Config $orderConfig)
    {
        $this->orderConfig = $orderConfig;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $statuses = $this->orderConfig->getStatuses();
        $options = [];
        foreach ($statuses as $code => $label) {
            $options[] = ['value' => $code, 'label' => $label];
        }

        return $options;
    }
}
