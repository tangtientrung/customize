<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace AHT\ProductQuestion\Controller\Adminhtml\Index;

use Magento\Framework\Controller\ResultFactory;


class Save extends \Magento\Backend\App\Action
{

    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'AHT_Question::question';
    
    /**
     * @var $_answerFactory;
     */
    protected $_answerFactory;
    /**
     * @var $_questionFactory;
     */
    protected $_questionFactory;

    /**
     * @var $_redirect;
     */
    protected $_redirect;

    /**
     * @param \AHT\ProductQuestion\Helper\Data
     */
    private $_helperData;
    /**
        * @var \Magento\Framework\App\Config\ScopeConfigInterface
    */
    protected $_scopeConfig;
    /**
        * @param  \Magento\Backend\App\Action\Context $context,
        * @param  \AHT\ProductQuestion\Model\ProductAnswerFactory $answerFactory,
        * @param \AHT\ProductQuestion\Model\ProductQuestionFactory $questionFactory,
        * @param\Magento\Framework\App\Response\RedirectInterface $redirect,
        * @param\AHT\ProductQuestion\Helper\Data $helperData,
        * @param\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \AHT\ProductQuestion\Model\ProductAnswerFactory $answerFactory,
        \AHT\ProductQuestion\Model\ProductQuestionFactory $questionFactory,
        \Magento\Framework\App\Response\RedirectInterface $redirect,
        \AHT\ProductQuestion\Helper\Data $helperData,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context);
        $this->_answerFactory = $answerFactory;
        $this->_questionFactory = $questionFactory;
        $this->_redirect = $redirect;
        $this->_helperData = $helperData;
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * Save blog record action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        //get id from url
        $request = $this->_redirect->getRefererUrl();
        $request =explode('/',$request);
        $question_id = $request[9];

        //get post data
        $data = $this->getRequest()->getParams();
        $question = $this->_questionFactory->create()->load($question_id);

        //check send mail
        if($question->getIsGetEmail() == "Yes")
        {
            //send mail
            // this is an example and you can change template id,fromEmail,toEmail,etc as per your need.
            $templateId = $this->_scopeConfig->getValue('aht/product/email_template', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);; // template id
            $fromEmail = $this->_scopeConfig->getValue('aht/product/email_sender', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);  // sender Email id
            $fromName = 'Admin';             // sender Name
            $toEmail = 'tientrung3600@gmail.com'; // receiver email id
            
            //check name if exit
            $name = $question->getName();
            if($name == "")
            {
                $name = "user";
            }

            $this->_helperData->sendEmail($templateId,$fromEmail,$fromName,$toEmail,$question->getQuestion(),strip_tags($data['answer']),$name);
        }

        //create new answer
        $answer = $this->_answerFactory->create();
        try{
            //save data
            $answer->setAnswer($data['answer']);
            $answer->setQuestionId($question_id);
            $answer->setStatus($data['status']);
            $answer->setCreatedAt(date('Y-m-d H:i:s'));
            $answer->setUpdatedAt(date('Y-m-d H:i:s'));
            $answer->save();

            //change status answer
            $question->setIsAnswer("Yes");
            $question->save();
            $this->messageManager->addSuccess(__('Add thành công'));
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/');
        }catch (\Exception $e){
            $this->messageManager->addError(__('Error '.$e));
        }
        
    }
}