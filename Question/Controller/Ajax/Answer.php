<?php
namespace AHT\Question\Controller\Ajax;

use Magento\Framework\Controller\ResultFactory;

class Answer extends \Magento\Framework\App\Action\Action
{
    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;
    protected $_json;
    protected $_resultJsonFactory;

    public function __construct(
		\Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Serialize\Serializer\Json $json,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
		)
	{
        $this->_json = $json;
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->_resultPageFactory = $resultPageFactory;
		return parent::__construct($context);
	}
    public function execute()
    {
        // //  //lấy dữ liệu từ ajax gửi sang
        // //  $response = $this->getRequest()->getParams();
        // //  /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        // //  $resultJson = $this->resultJsonFactory->create();
        // //   // chuyển kết quả về dạng object json và trả về cho ajax
        // //  return $resultJson->setData($response);
        // $response = $this->getRequest()->getParam('id');
        // return $response;
        /** @var \Magento\Framework\View\Layout $layout */
        // $layout = $this->_view->getLayout();

        // /** @var \Foo\Bar\Block\Popin\Content $block */
        // $block = $layout->createBlock(\Foo\Bar\Block\Popin\Content::class);
        // $block->setTemplate('Foo_Bar::popin/content.phtml');

        // $this->getResponse()->setBody($block->toHtml());

        $result = $this->_resultJsonFactory->create();
        $resultPage = $this->_resultPageFactory->create();
        $question_id = $this->getRequest()->getParam('id');
        $product_id = $this->getRequest()->getParam('product_id');
 
        $data = array(  'question_id' => $question_id,
                        'product_id' => $product_id
                        );
 
        $block = $resultPage->getLayout()
                ->createBlock('AHT\Question\Block\Ajax\Answer')
                ->setTemplate('AHT_Question::ajax/answer.phtml')
                ->setData('data',$data)
                ->toHtml();
 
        $result->setData(['output' => $block]);
        return $result;
    } 
}
 