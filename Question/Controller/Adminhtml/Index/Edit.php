<?php

namespace AHT\Question\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;

class Edit extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'AHT_Question::question';

    protected $_coreRegistry;
    protected $_pageFactory;

    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Framework\Registry $registry
    ) {
        $this->_pageFactory = $pageFactory;
        $this->_coreRegistry = $registry;
        parent::__construct($context);
    }

    /**
     * Load layout and set active menu
     */
    protected function _initAction()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('AHT_Question::question');
        return $resultPage;
    }

    public function execute()
    {
        // Get ID and create model
        $data = $this->getRequest()->getPostValue();
        $id = !empty($data['id']) ? $data['id'] : null;
        var_dump($id);

        // $model = $this->_objectManager->create('Trung\Banner\Model\Banner');

        // Initial checking
        // if ($id) {
        //     echo $id;
        //     // $model->load($id);

        //     // // If cannot get ID of model, display error message and redirect to List page
        //     // if (!$model->getId()) {
        //     //     $this->messageManager->addError(__('This image no longer exists.'));
        //     //     $resultRedirect = $this->resultRedirectFactory->create();
        //     //     return $resultRedirect->setPath('*/*/');
        //     // }
        // }

        // // Registry banner
        // $this->_coreRegistry->register('banner', $model);

        // Build form
        $resultPage = $this->_pageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Question'));
        return $resultPage;
    }
}
