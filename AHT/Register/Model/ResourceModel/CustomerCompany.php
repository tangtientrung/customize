<?php

namespace AHT\Register\Model\ResourceModel;

class CustomerCompany extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    protected function _construct()
    {
        // Table name + primary key column
        $this->_init('customer_company', 'entity_id');
    }

}