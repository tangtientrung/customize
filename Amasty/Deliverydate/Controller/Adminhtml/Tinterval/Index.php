<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */

namespace Amasty\Deliverydate\Controller\Adminhtml\Tinterval;


class Index extends \Amasty\Deliverydate\Controller\Adminhtml\Tinterval
{

    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_initAction();

        return $resultPage;
    }
}
