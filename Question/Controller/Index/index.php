<?php
namespace AHT\Question\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{
	protected $_pageFactory;

	protected $_questionFactory;
    protected $_resource;
    protected $_collection;
	protected $_customer;
    protected $_customerFactory;
	protected $_questionCollectionFactory;
	protected $_customerSession;

	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory,
		\AHT\Question\Model\QuestionFactory $questionFactory,
        \Magento\Framework\App\ResourceConnection $resource,
		\Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Model\Customer $customers,
		\Magento\Customer\Model\Session $customerSession,
		\AHT\Question\Model\ResourceModel\Question\CollectionFactory $questionCollectionFactory
		)
	{
		$this->_pageFactory = $pageFactory;
		$this->_questionFactory = $questionFactory;
		$this->_customerFactory = $customerFactory;
        $this->_customer = $customers;
		$this->_questionCollectionFactory = $questionCollectionFactory;
		$this->_customerSession = $customerSession;
		return parent::__construct($context);
	}

	public function execute()
	{
		echo $this->_customerSession->getCustomerData()->getId();
		// $question = $this->_questionCollectionFactory->create();
		// $customer=$this->_customerFactory->create();
		// $question->getSelect()->join(
		// 	['table1join'=>$question->getTable('customer_entity')],
		// 	'main_table.user_id = table1join.entity_id',
		// 	[])
		// 	->addFieldToFilter('main_table.status', array('eq' => '1'));
		// print_r($question->getData());
		// $id=0;
		// $question = $this->_questionCollectionFactory->create();
		// $question->getSelect()->join(
		// 	['table1join'=>$question->getTable('customer_entity')],
		// 	'main_table.user_id = table1join.entity_id',
		// 	array('*'))
		// 	->where("main_table.user_id = 0 AND main_table.question_id = 0 ");
		// 	// foreach($question as $item){
		// 	// 	echo "<pre>";
		// 	// 	print_r($item->getData());
		// 	// 	echo "</pre>";
		// 	// }
		// echo $question->getSelect()->__toString();
	}
}