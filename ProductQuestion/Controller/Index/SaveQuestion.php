<?php
 
namespace AHT\ProductQuestion\Controller\Index;
 
use Magento\Framework\App\Action;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Cache\Frontend\Pool;
use Magento\Framework\Controller\ResultFactory; 

class SaveQuestion extends \Magento\Framework\App\Action\Action
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
        \AHT\ProductQuestion\Model\ProductQuestionFactory $questionFactory,
        
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
        $data = $this->getRequest()->getParams();
        // create new questionFactory
        $question = $this->_questionFactory->create();
        // $questionResource = $this->_questionResource->create();
        try{
            $question->setProductId($data['product_id']);
            $question->setUserId($this->_customerSession->getCustomerData()->getId());
            $question->setQuestion($data['question']);
            $question->setName($data['name']);
            if(isset($data['is_get_email']))
            {
                $question->setIsGetEmail("Yes");
                $question->setEmail($data['email']);
            }
            else
            {
                $question->setIsGetEmail("No");
            }
            $question->setIsAnswer("No");
            $question->setCreatedAt(date('Y-m-d H:i:s'));
            $question->setUpdatedAt(date('Y-m-d H:i:s'));
            $question->save();
            // $questionResource->save($question);
            $this->messageManager->addSuccess(__('Đặt câu hỏi thành công!Vui lòng chờ admin trả lời câu hỏi của bạn'));
            // $this->cleanCache();
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
            return $resultRedirect;
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