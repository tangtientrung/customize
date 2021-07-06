<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */


namespace Amasty\Deliverydate\Controller\Adminhtml\Holidays;

class Edit extends \Amasty\Deliverydate\Controller\Adminhtml\Holidays
{

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->_objectManager->create('Amasty\Deliverydate\Model\Holidays');

        if ($id) {
            $this->resourceModel->load($model, $id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This item no longer exists.'));
                return $this->_redirect('amasty_deliverydate/*');
            }
        }
        // set entered data if was error when we do save
        $data = $this->session->getPageData(true);
        if (!empty($data)) {
            $model->addData($data);
        }

        $this->_coreRegistry->register('current_amasty_deliverydate_holidays', $model);

        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Amasty_Deliverydate::deliverydate_holidays');

        $title = $model->getId() ? __('Edit Exception') : __('New Exception');
        $resultPage->getConfig()->getTitle()->prepend($title);
        $resultPage->addBreadcrumb($title, $title);

        return $resultPage;

    }
}
