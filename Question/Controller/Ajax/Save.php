<?php
namespace AHT\Question\Controller\Ajax;

use Magento\Framework\Controller\ResultFactory;

class Save extends \Magento\Framework\App\Action\Action
{
    protected $json;
    protected $resultJsonFactory;
    protected $_questionFactory;
    protected $_customerSession;


    public function __construct(
		\Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Serialize\Serializer\Json $json,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Customer\Model\Session $customerSession,
        \AHT\Question\Model\QuestionFactory $questionFactory
		)
	{
        $this->_questionFactory = $questionFactory;
        $this->json = $json;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_customerSession = $customerSession;
		return parent::__construct($context);
	}
    public function execute()
    {
        //create new questionFactory
        $question = $this->_questionFactory->create();

        $id=$this->getRequest()->getParam('id');
        if($id)
        {
            $question->load($id);
            $question->setContent($this->getRequest()->getParam('content'));
            $question->save();
        }
        else {
            //get parent type
            $question_parent = $this->_questionFactory->create();
            $question_parent->load($this->getRequest()->getParam('question_id'));
            $type=$question_parent->getType()+1;

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
}
 