<?php
namespace AHT\Sa\Block\Frontend;

class Commission extends \Magento\Framework\View\Element\Template
{
    /**
     * @param \AHT\Sa\Model\SaFactory
     */
    private $_saFactory;

    /**
     * @param \AHT\Sa\Model\ResourceModel\Sa\CollectionFactory
     */
    private $_saCollectionFactory;

    /**
     * @param \Magento\Customer\Model\Session
     */
    private $_customerSession;

    /**
     * @param \Magento\Catalog\Model\ProductFactory
     */
    private $_productFactory;

    /**
     * @param \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $_productRepository;

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    private $_productCollectionFactory;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \AHT\Sa\Model\SaFactory $saFactory,
        \AHT\Sa\Model\ResourceModel\Sa\CollectionFactory $saCollectionFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        array $data = []
    ) {
        $this->_saFactory = $saFactory;
        $this->_saCollectionFactory = $saCollectionFactory;
        $this->_customerSession = $customerSession;
        $this->_productFactory = $productFactory;
        $this->_productRepository = $productRepository;
        $this->_productCollectionFactory = $productCollectionFactory;
        parent::__construct($context, $data);
    }
    public function getAll()
	{
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addAttributeToFilter('sale_agent_id',  array('notnull' => true));
        return $collection;
	}

    public function getUserId()
	{
		return $this->_customerSession->getCustomerData()->getId();
	}

    public function getProduct($id)
    {
        return $this->_productFactory->create()->load($id);
    }

    public function getProductUrl($id)
    {
        $product = $this->_productRepository->getById($id);
 
        return $product->getUrlKey();
    }
    public function getProductSku($id)
    {
        $product = $this->_productRepository->getById($id);
 
        return $product->getSku();
    }
    public function checkUser($product_id)
    {
        $product = $this->_productFactory->create()->load($product_id);
        return $product->getData('sale_agent_id');
    }
    public function getCommissionTypeText($product_id)
    {
        $product = $this->_productFactory->create()->load($product_id);
        return $product->getAttributeText('commission_type');
    }
}
