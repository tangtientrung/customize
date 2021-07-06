<?php

namespace AHT\Question\Block\Frontend;

class AccountQuestion extends \Magento\Framework\View\Element\Template
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
	protected $_customerSession;

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
		\Magento\Customer\Model\Session $customerSession,
		array $data = []
	)
	{
		$this->_questionFactory = $questionFactory;
		$this->_registry = $registry;
		$this->_questionCollectionFactory = $questionCollectionFactory;
		$this->_product = $product;
		$this->_urlInterface = $urlInterface;
		$this->httpContext = $httpContext;
		$this->_customerSession = $customerSession;
		parent::__construct($context, $data);
	}


	public function getAllQuestion()
	{
		$user_id = $this->getUserId();
		$question = $this->_questionCollectionFactory->create();
		$question->getSelect()->joinLeft('catalog_product_entity_varchar as pro','main_table.product_id = pro.entity_id AND pro.attribute_id = 73 ',array('*'))
		
			->where('main_table.type = 0')
			->where('main_table.user_id = '.$user_id);
		return $question;
		// var_dump($question);
		// die();
		// return $question->getSelect()->__toString();
	}
	public function getQuestion()
	{
		$id = $this->getRequest()->getParam('id');
		$question = $this->_questionCollectionFactory->create();
		$question->getSelect()->joinLeft('catalog_product_entity_varchar as pro','main_table.product_id = pro.entity_id AND pro.attribute_id = 73 ',array('*'))
			->where('main_table.question_id = '.$id);
		return $question;
		// var_dump($question);
		// die();
		// return $question->getSelect()->__toString();
	}
	public function getUserId()
	{
		return $this->_customerSession->getCustomerData()->getId();
	}
	public function getQuestionId()
	{
		return $this->getRequest()->getParam('id');
	}
	public function getAnswer($id)
	{
		$question = $this->_questionCollectionFactory->create();
		$question->getSelect()->joinLeft('catalog_product_entity_varchar as pro','main_table.product_id = pro.entity_id AND pro.attribute_id = 73 ',array('*'))
			->where('main_table.question_id = '.$id);
		return $question;
		// var_dump($question);
		// die();
		// return $question->getSelect()->__toString();
	}
}