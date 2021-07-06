<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */

namespace Amasty\Deliverydate\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\VersionControl\AbstractDb;

class Deliverydate extends AbstractDb
{
    const MAIN_TABLE = 'amasty_amdeliverydate_deliverydate';

    protected function _construct()
    {
        $this->_init(self::MAIN_TABLE, 'deliverydate_id');
    }
}
