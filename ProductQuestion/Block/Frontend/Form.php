<?php

namespace AHT\ProductQuestion\Block\Frontend;

class Form extends \Magento\Framework\View\Element\Template
{
	/**
     * @var \AHT\ProductQuestion\Model\ProductQuestionFactory
     */
	protected $_questionFactory;

	/**
     * @var \Magento\Framework\Registry
     */
	protected $_registry;

	/**
     * @var \AHT\ProductQuestion\Model\ResourceModel\ProductQuestion\CollectionFactory
     */
	protected $_questionCollectionFactory;

	/**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
	protected $_httpContext;

	/**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

	/**
     * @param \AHT\ProductQuestion\Model\ResourceModel\ProductQuestion\Collection
     */
    private $_answerCollectionFactory;

	/**
     * @param \Magento\Customer\Model\Session
     */
    private $_customer;

	/**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \AHT\ProductQuestion\Model\ProductQuestionFactory $questionFactory
	 * @param array $data
	 * @param \Magento\Framework\Registry $registry
	 * @param \AHT\ProductQuestion\Model\ResourceModel\ProductQuestion\CollectionFactory $questionCollectionFactory,
     * @param \Magento\Framework\App\Http\Context $httpContext
	 * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig  
	 */
	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
		\AHT\ProductQuestion\Model\ProductQuestionFactory $questionFactory,
		\AHT\ProductQuestion\Model\ResourceModel\ProductQuestion\CollectionFactory $questionCollectionFactory,
		\AHT\ProductQuestion\Model\ResourceModel\ProductAnswer\CollectionFactory $answerCollectionFactory,
		\Magento\Framework\Registry $registry,
		\Magento\Framework\App\Http\Context $httpContext,
		\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
		array $data = []
	)
	{
		$this->_questionFactory = $questionFactory;
		$this->_registry = $registry;
		$this->_questionCollectionFactory = $questionCollectionFactory;
		$this->_answerCollectionFactory = $answerCollectionFactory;
		$this->_httpContext = $httpContext;
		$this->_scopeConfig = $scopeConfig;
		parent::__construct($context, $data);
	}

	
	public function getCurrentProduct()
    {       
        return $this->_registry->registry('current_product');
    }   
	public function isLoggedIn()
    {
        $isLoggedIn = $this->_httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
		$check = $this->_scopeConfig->getValue('aht/product/sign_in_to_ask_question', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		
		if($check == 0)
		{
			return true;
		}
		else if($check == 1 && $isLoggedIn ==true)
		{
			return true;
		}
		return false;
	
	}

	public function getAllQuestion()
	{
		$product_id=$this->getCurrentProduct()->getId();
		$question = $this->_questionCollectionFactory->create();
		$question->addFieldToFilter('product_id', array('eq'=>$product_id));
		$question->addFieldToFilter('status', array('eq'=>1));
		return $question;
	}

	public function getAnswer($id)
	{
		$answer = $this->_answerCollectionFactory->create();
		$answer->addFieldToFilter('question_id', array('eq'=>$id));
		$answer->addFieldToFilter('status', array('eq'=>1));
		return $answer;
	}
	
	public function getCustomerId()
    {
    	return $this->_httpContext->getValue('customer_id');
    }

	public function getCustomerName()
    {
    	return $this->_httpContext->getValue('customer_name');
    }

    public function getCustomerEmail()
    {
    	return $this->_httpContext->getValue('customer_email');
    }

}