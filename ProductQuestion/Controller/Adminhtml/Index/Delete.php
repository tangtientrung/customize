<?php
namespace AHT\ProductQuestion\Controller\Adminhtml\Index;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'AHT_Question::question';

    /**
     * @param \Magento\Framework\App\CacheInterface
     */
    private $_cache;

    /**
     * @param \AHT\ProductQuestion\Helper\Data
     */
    private $_helperData;

    /**
     * @param \Magento\Backend\App\Action\Context
     * @param \Magento\Framework\App\CacheInterface
     * @param \AHT\ProductQuestion\Helper\Data
	 */
    public function __construct(
        \Magento\Backend\App\Action\Context $context, 
        \Magento\Framework\App\CacheInterface $cache,
        \AHT\ProductQuestion\Helper\Data $helperData)
    {
        $this->_cache = $cache;
        $this->_helperData = $helperData;
        parent::__construct($context);
    }

    public function execute()
    {
        // Get ID of record by param
        $id = $this->getRequest()->getParam('id');

        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                // Init model and delete
                $model = $this->_objectManager->create('AHT\ProductQuestion\Model\ProductQuestion');
                $model->load($id);
                $model->delete();

                // //clean cache
                // //flush cache
                // $this->_cache->clean('catalog_product_' .$id);
                //flush cache
                $this->_helperData->flushCache();

                // Display success message
                $this->messageManager->addSuccess(__('The question has been deleted.'));

                // Redirect to list page
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // Display error message
                $this->messageManager->addError($e->getMessage());
                // Go back to edit form
                return $resultRedirect->setPath('*/*/');
            }
        }

        // Display error message
        $this->messageManager->addError(__('We can\'t find a question to delete.'));

        // Redirect to list page
        return $resultRedirect->setPath('*/*/');
    }
}
