<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace AHT\Question\Controller\Adminhtml\Index;

use Magento\Framework\Controller\ResultFactory;

class Save extends \Magento\Backend\App\Action
{

    /**
     * @var Question
     */
    protected $_questionModel;

    // /**
    //  * @var Session
    //  */
    // protected $_adminSession;

    /**
     * @param Action\Context $context
     * @param questionModel           $questionModel
     * @param Session        $adminsession
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \AHT\Question\Model\Question $questionModel
        // ,\Magento\Backend\Model\Session $adminsession
    ) {
        parent::__construct($context);
        $this->_questionModel = $questionModel;
        // $this->_adminsession = $adminsession;
    }

    /**
     * Save blog record action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {
            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $this->_questionModel->load($id);
            }

            $this->_questionModel->setData($data);

            try {
                $this->_questionModel->save();
                $this->messageManager->addSuccess(__('Thành công'));
                // $this->adminsession->setFormData(false);

                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the data.'));
            }

        }

        
        
        return $this->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true]);
    }
}