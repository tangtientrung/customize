<?php
namespace AHT\Question\Controller\Ajax;

use Magento\Framework\Controller\ResultFactory;

class Delete extends \Magento\Framework\App\Action\Action
{
    protected $_questionFactory;

    public function __construct(
		\Magento\Framework\App\Action\Context $context,
        \AHT\Question\Model\QuestionFactory $questionFactory
		)
	{
        $this->_questionFactory = $questionFactory;
		return parent::__construct($context);
	}
    public function execute()
    {
        //create new questionFactory
        $question = $this->_questionFactory->create();

        $id=$this->getRequest()->getParam('id');
        $question->load($id);
        $question->delete();
    } 
}
 