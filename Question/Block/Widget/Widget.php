<?php

namespace AHT\Question\Block\Widget;

class Widget extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
	 /**
     * @var $_questionFactory
	 * @var $_registry
	 * @var $_questionCollectionFactory
	 * @var $product
	 * @var $_urlInterface;
     */
	protected $_questionFactory;
	protected $_registry;
	protected $_questionCollectionFactory;
	protected $_product; 
	protected $_urlInterface; 
	protected $httpContext;
	protected $_coreSession;
	protected $_customerSession;
    protected $_productRepositoryFactory;
	protected $_imageHelper;
    protected $_productCollection;
    protected $_productrepository;
	/**
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \AHT\Question\Model\QuestionFactory $questionFactory
	 * @param array $data
	 * @param \Magento\Framework\Registry $registry
	 * @param \AHT\Question\Model\ResourceModel\Question\CollectionFactory $questionCollectionFactory,
     * @param \Magento\Catalog\Model\ProductFactory $product
	 * @param \Magento\Framework\UrlInterface $urlInterface,   
	 */
	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
		\AHT\Question\Model\QuestionFactory $questionFactory,
		\AHT\Question\Model\ResourceModel\Question\CollectionFactory $questionCollectionFactory,
		\Magento\Framework\Registry $registry,
		\Magento\Catalog\Model\ProductFactory $product,
		\Magento\Framework\UrlInterface $urlInterface,   
		\Magento\Framework\App\Http\Context $httpContext,
		\Magento\Framework\Session\SessionManagerInterface $coreSession,
		\Magento\Customer\Model\Session $customerSession,
        \Magento\Catalog\Model\ProductFactory $productRepositoryFactory,
        \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection, 
        \Magento\Catalog\Helper\Image $imageHelper,
		\Magento\Catalog\Api\ProductRepositoryInterface $productrepository,
        
		array $data = []
	)
	{
		$this->_questionFactory = $questionFactory;
		$this->_registry = $registry;
		$this->_questionCollectionFactory = $questionCollectionFactory;
		$this->_product = $product;
		$this->_urlInterface = $urlInterface;
		$this->httpContext = $httpContext;
		$this->_coreSession = $coreSession;
		$this->_customerSession = $customerSession;
        $this->_productRepositoryFactory = $productRepositoryFactory;
        $this->_imageHelper = $imageHelper;
        $this->_productCollection = $productCollection;
        $this->_productrepository = $productrepository;
		parent::__construct($context, $data);
	}


	public function getAllQuestion($product_id)
	{
		// $product_id=$this->getCurrentProduct()->getId();
		$question = $this->_questionCollectionFactory->create();

		$question->getSelect()->join(
			['table1join'=>$question->getTable('customer_entity')],
			'main_table.user_id = table1join.entity_id',
			array('*'))
            ->joinLeft('catalog_product_entity_varchar as pro','main_table.product_id = pro.entity_id AND pro.attribute_id = 73 ',array('*'))
			->where('main_table.status = 1')
			->where('main_table.type = 0')
            ->where('main_table.product_id = '.$product_id);
		return $question;
        // return $question->getSelect()->__toString();
	}

    public function getAllProduct()
	{
		// $product_id=$this->getCurrentProduct()->getId();
		$product_id = $this->_questionCollectionFactory->create();
		$product_id->getSelect()
                ->joinLeft('catalog_product_entity_varchar as pro','main_table.product_id = pro.entity_id AND pro.attribute_id = 73 ',array('*'))
                ->columns('product_id')
                ->group('product_id')
                ->where("main_table.status = 1 AND main_table.type = 0");
		return $product_id;
        // return $product_id->getSelect()->__toString();
	}
	public function getProductImage($id)
    {
        $product = $this->_productRepositoryFactory->create()->load($id);
        $url = $this->_imageHelper->init($product, 'category_page_list')->getUrl();
        return $url;
        
        // $product->getData('thumbnail');
        // $product->getData('small_image');
    }
    public function getProductUrl($id)
    {
        $product = $this->_productrepository->getById($id);
 
        return $product->getUrlKey();
    }
	public function getOthersAnswer($id)
	{
		$answer = $this->_questionCollectionFactory->create();
		$answer->getSelect()->join(
			['table1join'=>$answer->getTable('customer_entity')],
			'main_table.user_id = table1join.entity_id',
			array('*'))
			->where("main_table.status = 1 AND main_table.user_id != 0  AND main_table.question_id = ".$id);
		return $answer;
        // return $answer->getSelect()->__toString();
	}
	
}