<?php
 
namespace AHT\Question\Controller\Adminhtml\Index;
 
class Index extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'AHT_Question::question';

    /**
     * @var $_pageFactory
     */
    protected $_pageFactory;

    /**
     * @param Magento\Backend\App\Action\Context
	 * \Magento\Framework\View\Result\PageFactory   
	 */
    public function __construct(
        \Magento\Backend\App\Action\Context $context, 
        \Magento\Framework\View\Result\PageFactory $pageFactory)
    {
        $this->_pageFactory = $pageFactory;
        parent::__construct($context);
    }

    /**
     * Init page
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->_pageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Question'));
        return $resultPage;
    }

}