<?php
 
namespace AHT\Question\Controller\Adminhtml\Admin;
 
use Magento\Framework\Controller\ResultFactory; 
 
class Form extends \Magento\Backend\App\Action
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
     * @var \Magento\Framework\Registry
    */
 
    protected $_registry;
    protected $_coreSession;
    /**
     * @param Magento\Backend\App\Action\Context
	 * \Magento\Framework\View\Result\PageFactory   
	 */
    public function __construct(
        \Magento\Backend\App\Action\Context $context, 
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Session\SessionManagerInterface $coreSession
        )
    {
        $this->_pageFactory = $pageFactory;
        parent::__construct($context);
        $this->_registry = $registry;
        $this->_coreSession = $coreSession;
    }

    /**
     * Init page
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $this->setValue();
        $resultPage = $this->_pageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Answer of admin'));
        return $resultPage;
        
    
    }
    public function setValue(){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $request = $objectManager->get('Magento\Framework\App\Request\Http');
        $request = $request->getServer('HTTP_REFERER');
        
        $this->_coreSession->start();
        $this->_coreSession->setMyVariable($request);
    }
    /**
     * Setting custom variable in registry to be used
     *
     */
    
    public function setCustomVariable()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $request = $objectManager->get('Magento\Framework\App\Request\Http');
        $request = $request->getServer('HTTP_REFERER');
        $request =explode('/',$request);
        $this->_registry->register('id', $request[9]);
    }
    
    /**
     * Retrieving custom variable from registry
     * @return string
     */
    public function getCustomVariable()
    {
        return $this->_registry->registry('id');
    }

}