<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace AHT\Question\Controller\Adminhtml\Answer;

use Magento\Framework\Controller\ResultFactory;


class Save extends \Magento\Backend\App\Action
{

    /**
     * @var Question
     */
    protected $_questionFactory;

    /**
     * @var _coreSession
     */
    protected $_coreSession;

    /**
     * @param Action\Context $context
     * @param questionModel           $questionModel
     * @param Session        $adminsession
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \AHT\Question\Model\QuestionFactory $questionFactory,
        \Magento\Framework\Session\SessionManagerInterface  $coreSession
        // ,\Magento\Backend\Model\Session $adminsession
    ) {
        parent::__construct($context);
        $this->_questionFactory = $questionFactory;
        $this->_coreSession = $coreSession;
        // $this->_adminsession = $adminsession;
    }

    /**
     * Save blog record action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $this->_coreSession->start();
        $request = $this->_coreSession->getMyVariable();
        $request =explode('/',$request);
        // $url = implode("/")
        $question_id = $request[9];
        //create new question
        $question = $this->_questionFactory->create();
        $question_parent = $this->_questionFactory->create();
        if($question_id)
        {
            $question_parent->load($question_id);
                try{
                if(!isset($_POST['id']))
                {
                    $type=$question_parent->getType()+1;
                    $question->setContent($_POST['content']);
                    $question->setQuestionId($question_id);
                    $question->setUserId(0);
                    $question->setProductId($question_parent->getProductId());
                    $question->setStatus($_POST['status']);
                    $question->setType($type);
                    $question->save();
                    $this->messageManager->addSuccess(__('Add thành công'));
                }
                // else
                // {
                //     $id=$_POST['id'];
                //     $post->load($id);
                //     $post->setName($_POST['name']);
                //     $post->setUrl($_POST['url']);
                //     $post->setImage($_POST['image']);
                //     $post->setContent($_POST['content']);
                //     $post->setStatus($_POST['status']);
                //     $post->setUpdatedAt(date('Y-m-d H:i:s'));
                //     $post->save();
                //     $this->messageManager->addSuccess(__('Edit thành công'));
                // }
                $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
                $resultRedirect->setUrl($this->_redirect->getRefererUrl());
                
                return $resultRedirect;
            }catch (\Exception $e){
                $this->messageManager->addError(__('Error '.$e));
            }
        }
        
    }
}