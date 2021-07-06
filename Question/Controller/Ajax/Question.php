<?php
namespace AHT\Question\Controller\Ajax;

use Magento\Framework\Controller\ResultFactory;

class Question extends \Magento\Framework\App\Action\Action
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
        // die();
        //create new questionFactory
        $question = $this->_questionFactory->create();

        $question->setProductId($this->getRequest()->getParam('product_id'));
        $question->setUserId($this->_customerSession->getCustomerData()->getId());
        $question->setContent($this->getRequest()->getParam('content'));
        // $question->setStatus(1);
        $question->setCreatedAt(date('Y-m-d H:i:s'));
        $question->setUpdatedAt(date('Y-m-d H:i:s'));
        $question->save();
        // $this->messageManager->addSuccess(__('Đặt câu hỏi thành công!Vui lòng chờ admin xác nhận câu hỏi của bạn'));
        // return "ok";
        // return $this->getRequest()->getParam('product_id');;
        // echo "ok";
        // echo 'ok';
    } 
}
 