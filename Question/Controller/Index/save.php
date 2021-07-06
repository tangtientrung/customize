<?php
 
namespace AHT\Question\Controller\Index;
 
use Magento\Framework\App\Action;
use AHT\Question\Model\QuestionFactory;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Cache\Frontend\Pool;

class Save extends \Magento\Framework\App\Action\Action
{
    /*
    * @var $resultRedirect
    * @var $formFactory
    * @var $_customerSession;
    */
    protected $_questionFactory;
    protected $_cacheTypeList;
    protected $_cacheFrontendPool;
    protected $_customerSession;

    /*
    * @param Action\Context $context
    * @param FormFactory $formFactory
    * @param \Magento\Customer\Model\Session $customerSession
    */
    public function __construct(
        Action\Context $context,
        QuestionFactory $questionFactory,
        TypeListInterface $cacheTypeList,
        \Magento\Customer\Model\Session $customerSession,
        Pool $cacheFrontendPool
    )
    {
        $this->_questionFactory = $questionFactory;
        parent::__construct($context);
        $this->_cacheTypeList = $cacheTypeList;
        $this->_cacheFrontendPool = $cacheFrontendPool;
        $this->_customerSession = $customerSession;
    }
 
    public function execute()
    {
        //create new questionFactory
        $question = $this->_questionFactory->create();
        
        try{
            //new question
            if(!isset($_POST['id']))
            {
                $question->setProductId($_POST['product_id']);
                $question->setUserId($this->_customerSession->getCustomerData()->getId());
                $question->setContent($_POST['question']);
                $question->setCreatedAt(date('Y-m-d H:i:s'));
                $question->setUpdatedAt(date('Y-m-d H:i:s'));
                $question->save();
                $this->messageManager->addSuccess(__('Đặt câu hỏi thành công!Vui lòng chờ admin xác nhận câu hỏi của bạn'));
            }
            // else
            // {
            //     $id=$_POST['id'];
            //     $question->load($id);
            //     $question->setName($_POST['name']);
            //     $question->setUrl($_POST['url']);
            //     $question->setImage($_POST['image']);
            //     $question->setContent($_POST['content']);
            //     $question->setStatus($_POST['status']);
            //     $question->setUpdatedAt(date('Y-m-d H:i:s'));
            //     $question->save();
            //     $this->messageManager->addSuccess(__('Edit thành công'));
            // }
            $this->cleanCache();
            return $this->_redirect($_POST['url']);
        }catch (\Exception $e){
            $this->messageManager->addError(__('Error '.$e));
        }
        
    }
    public function cleanCache()
    {
        $types = array('config','layout','block_html','collections','reflection','db_ddl','eav','config_integration','config_integration_api','full_page','translate','config_webservice');
    
        foreach ($types as $type) {
            $this->_cacheTypeList->cleanType($type);
        }
        foreach ($this->_cacheFrontendPool as $cacheFrontend) {
            $cacheFrontend->getBackend()->clean();
        }
    }
}