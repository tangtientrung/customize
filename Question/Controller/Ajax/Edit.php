<?php
namespace AHT\Question\Controller\Ajax;

class Edit extends \Magento\Framework\App\Action\Action
{
 
    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;
    protected $_resultJsonFactory;
 
    /**
     * View constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context, 
        \Magento\Framework\View\Result\PageFactory $resultPageFactory, 
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
        )
    {
 
        $this->_resultPageFactory = $resultPageFactory;
        $this->_resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }
 
 
    /**
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $result = $this->_resultJsonFactory->create();
        $resultPage = $this->_resultPageFactory->create();
        $id = $this->getRequest()->getParam('id');
        $content = trim($this->getRequest()->getParam('content'));
        $className = $this->getRequest()->getParam('className');

        $data = array(  'id'=>$id,
                        'content'=>$content,
                        'className'=>$className);
 
        $block = $resultPage->getLayout()
                ->createBlock('AHT\Question\Block\Frontend\Question')
                ->setTemplate('AHT_Question::ajax/edit.phtml')
                ->setData('data',$data)
                ->toHtml();
 
        $result->setData(['output' => $block]);
        return $result;
    }

   
}