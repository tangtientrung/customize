<?php

namespace AHT\Register\Model;

class CustomerCompany extends \Magento\Framework\Model\AbstractModel
{

    protected function _construct()
    {
        $this->_init('AHT\Register\Model\ResourceModel\CustomerCompany');
    }

}
