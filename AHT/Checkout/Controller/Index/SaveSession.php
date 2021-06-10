<?php
namespace AHT\Checkout\Controller\Index;

class SaveSession extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_pageFactory;

     /**
     * @param \Magento\Framework\Serialize\Serializer\Json
     */
    private $_json;

    /**
     * @param \Magento\Framework\Controller\Result\JsonFactory
     */
    private $_jsonFactory;

    /**
     * @param \Magento\Checkout\Model\Session
     */
    private $_checkoutSession;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
       \Magento\Framework\App\Action\Context $context,
       \Magento\Framework\View\Result\PageFactory $pageFactory,
       \Magento\Framework\Serialize\Serializer\Json $json,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
        \Magento\Checkout\Model\Session $checkoutSession
    )
    {
        $this->_json = $json;
        $this->_jsonFactory = $jsonFactory;
        $this->_checkoutSession = $checkoutSession;
        $this->_pageFactory = $pageFactory;
        return parent::__construct($context);
    }
    /**
     * View page action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getContent();
        $response = $this->_json->unserialize($data);
        // var_dump($data);
        // die();
        // $response = $response['date'];
        $date = $this->setDate($response['date']);
        $comment = $this->setComment($response['comment']);
        // $resultJson = $this->_jsonFactory->create();
        // return $resultJson->setData('true');

    }

    
    public function setDate($date) 
    {
        return $this->_checkoutSession->setDate($date);
    }
    public function setComment($comment) 
    {
        return $this->_checkoutSession->setComment($comment);
    }
}
