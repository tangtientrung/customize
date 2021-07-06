<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */

namespace Amasty\Deliverydate\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\VersionControl\AbstractDb;

class Tinterval extends AbstractDb
{

    protected function _construct()
    {
        $this->_init('amasty_amdeliverydate_tinterval', 'tinterval_id');
    }
}
