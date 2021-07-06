<?php

namespace AHT\Question\Controller\Adminhtml\Index;

class InlineEdit extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'AHT_Question::question';

    /**
     * @var $_questionFactory
     * @var $_jsonFactory
     */
    protected $_questionFactory;
    protected $_jsonFactory;

    /**
     * @param \Magento\Backend\App\Action\Context
     * @param \Magento\Framework\Controller\Result\JsonFactory
     * @param \AHT\Question\Model\QuestionFactory
	 */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \AHT\Question\Model\QuestionFactory $questionFactory,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
    )
    {
        parent::__construct($context);
        $this->_questionFactory = $questionFactory;
        $this->_jsonFactory = $jsonFactory;
    }

    public function execute()
    {
        // Init result Json
        $resultJson = $this->_jsonFactory->create();
        $error = false;
        $messages = [];

        // Get POST data
        $postItems = $this->getRequest()->getParam('items', []);

        // Check request
        if (!($this->getRequest()->getParam('isAjax') && count($postItems))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }

        // Save data to database
        foreach (array_keys($postItems) as $questionId) {
            try {
                $question = $this->_questionFactory->create();
                $question->load($questionId);
                $question->setData($postItems[(string)$questionId]);
                $question->save();
            } catch (\Exception $e) {
                $messages[] = __('Something went wrong while saving data.');
                $error = true;
            }
        }

        // Return result Json
        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }
}