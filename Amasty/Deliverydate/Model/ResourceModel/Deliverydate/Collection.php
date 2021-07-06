<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */


namespace Amasty\Deliverydate\Model\ResourceModel\Deliverydate;

class Collection extends \Amasty\Deliverydate\Model\ResourceModel\DateCollectionAbstract
{
    protected function _construct()
    {
        $this->_init('Amasty\Deliverydate\Model\Deliverydate', 'Amasty\Deliverydate\Model\ResourceModel\Deliverydate');
    }

    public function getOlderThan($start)
    {
        $this->getSelect()
            ->where('`date` <> \'0000-00-00\'')
            ->where('`date` <> \'1970-01-01\'')
            ->where('`date` >= ?', $start)
            ->where('`active` = \'1\'');

        return $this;
    }

    public function joinTinterval()
    {
        $this->getSelect()
            ->joinLeft(
                ['ti' => $this->getTable('amasty_amdeliverydate_tinterval')],
                'main_table.tinterval_id = ti.tinterval_id',
                ['qty_order' => 'COUNT(main_table.deliverydate_id)']
            )
            ->where('ti.quota > 0 AND main_table.date is not null')
            ->group('ti.tinterval_id')
            ->group('main_table.date')
            ->having('qty_order >= MAX(ti.quota)');

        return $this;
    }
}
