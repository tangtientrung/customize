<?php
namespace OmnyfyCustomization\MpcIntegration\Controller\Registration;

class ValidateEmail extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_pageFactory;

    /**
     * @param \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory
     */
    private $_collectionFactory;

    /**
     * @param \Magento\Framework\Controller\Result\JsonFactory
     */
    private $_jsonFactory;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
       \Magento\Framework\App\Action\Context $context,
       \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $collectionFactory,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
    )
    {
        $this->_jsonFactory = $jsonFactory;
        $this->_pageFactory = $pageFactory;
        $this->_collectionFactory = $collectionFactory;
        return parent::__construct($context);
    }
    /**
     * View page action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $email = $this->getRequest()->getParam('email');
        $collection = $this->_collectionFactory->create()->addFieldToFilter('email', $email)->load();
        if($collection->getData())
        {
            $data = "exit";
            $resultJson = $this->_jsonFactory->create();
            return $resultJson->setData($data);
        }
        
    }
}
