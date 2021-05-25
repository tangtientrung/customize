<?php

namespace AHT\Register\Model\ResourceModel\CustomerCompany;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    protected $_idFieldName = 'entity_id';

    protected function _construct()
    {
        // Model + Resource Model
        $this->_init('AHT\CustomerCompany\Model\CustomerCompany', 'AHT\CustomerCompany\Model\ResourceModel\CustomerCompany');
    }

}