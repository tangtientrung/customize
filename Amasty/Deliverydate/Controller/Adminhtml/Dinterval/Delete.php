<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */

namespace Amasty\Deliverydate\Controller\Adminhtml\Dinterval;

use Amasty\Deliverydate\Controller\Adminhtml\Dinterval\Index;

class Delete extends Index
{
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        try {
            $id = $this->getRequest()->getParam('id');
            $ddModel = $this->model->create();
            $this->resourceModel->load($ddModel, $id);
            $this->resourceModel->delete($ddModel);
            $this->messageManager->addSuccessMessage(__('Date interval has been deleted.'));
        } catch (\Exception $e) {
            // display error message
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        return $resultRedirect->setPath('*/*/');
    }
}