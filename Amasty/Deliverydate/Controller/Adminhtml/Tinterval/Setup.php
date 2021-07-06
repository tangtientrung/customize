<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */


namespace Amasty\Deliverydate\Controller\Adminhtml\Tinterval;

class Setup extends \Amasty\Deliverydate\Controller\Adminhtml\Tinterval
{

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Amasty_Deliverydate::deliverydate_tinterval');

        $title =  __('Generate Time Intervals');
        $resultPage->getConfig()->getTitle()->prepend($title);
        $resultPage->addBreadcrumb($title, $title);

        return $resultPage;

    }
}
