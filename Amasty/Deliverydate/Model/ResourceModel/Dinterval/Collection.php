<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */


namespace Amasty\Deliverydate\Model\ResourceModel\Dinterval;

class Collection extends \Amasty\Deliverydate\Model\ResourceModel\DateCollectionAbstract
{
    protected function _construct()
    {
        $this->_init('Amasty\Deliverydate\Model\Dinterval', 'Amasty\Deliverydate\Model\ResourceModel\Dinterval');
        $this->_setIdFieldName($this->getResource()->getIdFieldName());
    }

    /**
     * @param int $currentStoreId
     *
     * @return \Amasty\Deliverydate\Model\Dinterval[]
     */
    public function filterByStore($currentStoreId)
    {
        $dintervals = [];

        foreach ($this as $item) {
            $storeIds = trim($item->getData('store_ids'), ',');
            $storeIds = explode(',', $storeIds);
            if (!in_array($currentStoreId, $storeIds) && !in_array(0, $storeIds)) {
                continue;
            }
            $dintervals[] = $item;
        }

        return $dintervals;
    }
}
