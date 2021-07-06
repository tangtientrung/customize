<?php
namespace AHT\Question\Controller\Ajax;

use Magento\Framework\Controller\ResultFactory;

class SaveAnswer extends \Magento\Framework\App\Action\Action
{
    protected $_questionFactory;
    protected $_customerSession;


    public function __construct(
		\Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \AHT\Question\Model\QuestionFactory $questionFactory
		)
	{
        $this->_questionFactory = $questionFactory;
        $this->_customerSession = $customerSession;
		return parent::__construct($context);
	}
    public function execute()
    {
        //get parent type
        $question_parent = $this->_questionFactory->create();
        $question_parent->load($this->getRequest()->getParam('question_id'));
        $type=$question_parent->getType()+1;

        //create new questionFactory
        $question = $this->_questionFactory->create();
        
        //new answer
        $question->setQuestionId($this->getRequest()->getParam('question_id'));
        $question->setProductId($this->getRequest()->getParam('product_id'));
        $question->setUserId($this->_customerSession->getCustomerData()->getId());
        $question->setContent($this->getRequest()->getParam('content'));
        $question->setType($type);
        $question->setStatus(1);
        $question->save();
    } 
}
 