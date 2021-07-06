<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */

namespace Amasty\Deliverydate\Model\Config\Source;

class Group implements \Magento\Framework\Option\ArrayInterface
{

    protected $_options;
    protected $_groupFactory;

    public function __construct(\Magento\Customer\Model\ResourceModel\Group\CollectionFactory $groupFactory)
    {
        $this->_groupFactory = $groupFactory;
    }

    public function toOptionArray()
    {
        if (!$this->_options) {
            /** @var $stores \Magento\Customer\Model\ResourceModel\Group\Collection */
            $stores = $this->_groupFactory->create();
            $this->_options = $stores->load()->toOptionArray();
        }
        return $this->_options;
    }
}
