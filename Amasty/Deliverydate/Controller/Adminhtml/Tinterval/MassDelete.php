<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */

namespace Amasty\Deliverydate\Controller\Adminhtml\Tinterval;

use Amasty\Deliverydate\Controller\Adminhtml\Tinterval\Index;
use Magento\Framework\Controller\ResultFactory;

class MassDelete extends Index
{
    /**
     * @return mixed
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->tintervalCollection);
        $collectionSize = $collection->getSize();

        foreach ($collection as $tinterval) {
            $tinterval->delete();
        }
        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $collectionSize));
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}