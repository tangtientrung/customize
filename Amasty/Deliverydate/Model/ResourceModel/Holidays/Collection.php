<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */


namespace Amasty\Deliverydate\Model\ResourceModel\Holidays;

class Collection extends \Amasty\Deliverydate\Model\ResourceModel\DateCollectionAbstract
{
    protected function _construct()
    {
        $this->_init('Amasty\Deliverydate\Model\Holidays', 'Amasty\Deliverydate\Model\ResourceModel\Holidays');
        $this->_setIdFieldName($this->getResource()->getIdFieldName());
    }
}
