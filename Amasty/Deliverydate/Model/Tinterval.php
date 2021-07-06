<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */

namespace Amasty\Deliverydate\Model;

use Amasty\Deliverydate\Helper\Data as DeliverydateHelper;

class Tinterval extends \Magento\Framework\Model\AbstractModel
{

    /**
     * @var DeliverydateHelper
     */
    private $helper;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        DeliverydateHelper $helper,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->helper = $helper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    protected function _construct()
    {
        parent::_construct();
        $this->_init('Amasty\Deliverydate\Model\ResourceModel\Tinterval');
        $this->setIdFieldName('tinterval_id');
    }

    /**
     * @return string
     */
    public function getStartTime()
    {
        $deliveryDateTime = $this->helper->getDeliveryDataWithOffsets();
        return $deliveryDateTime->getTime()->toString('HH:mm');
    }

    /**
     * @param $optionsForCurrentDay
     * @return mixed
     */
    public function restrictCurrentTinterval($optionsForCurrentDay)
    {
        if ($this->helper->getDisabledCurrentTimeInteval() && $optionsForCurrentDay) {
            unset($optionsForCurrentDay[0]);
        }

        return $optionsForCurrentDay;
    }
}
