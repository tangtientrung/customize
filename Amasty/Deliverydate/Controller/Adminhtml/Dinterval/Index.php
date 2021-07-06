<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */


namespace Amasty\Deliverydate\Controller\Adminhtml\Dinterval;

class Index extends \Amasty\Deliverydate\Controller\Adminhtml\Dinterval
{

    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page */
        return $this->_initAction();
    }
}
