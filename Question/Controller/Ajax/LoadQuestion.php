<?php
namespace AHT\Question\Controller\Ajax;

class LoadQuestion extends \Magento\Framework\App\Action\Action
{
 
    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;
    protected $_resultJsonFactory;
    protected $_coreSession;
    protected $_registry;
 
    /**
     * View constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context, 
        \Magento\Framework\View\Result\PageFactory $resultPageFactory, 
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Session\SessionManagerInterface $coreSession
        )
    {
 
        $this->_resultPageFactory = $resultPageFactory;
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->_coreSession = $coreSession;
        $this->_registry = $registry;
        parent::__construct($context);
    }
 
 
    /**
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $result = $this->_resultJsonFactory->create();
        $resultPage = $this->_resultPageFactory->create();
        $currentProductId = $this->getRequest()->getParam('id');
        // $currentProductId=436;

        //set session product_id
        // $this->setValue($currentProductId);

        // $data = array('currentproductid'=>$currentProductId);
 
        $block = $resultPage->getLayout()
                ->createBlock('AHT\Question\Block\Frontend\Question')
                ->setTemplate('AHT_Question::ajax/question.phtml')
                ->setData('data',$currentProductId)
                ->toHtml();
 
        $result->setData(['output' => $block]);
        return $result;
    }

    public function setValue($id){
        // $this->_coreSession->start();
        // $this->_coreSession->setMyVariable($id);
        $this->_registry->register('product_id', $id);
    }
}