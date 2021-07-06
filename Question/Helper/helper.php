<?php

namespace AHT\Question\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;

class Helper extends AbstractHelper
{
    /*
    * @var $_cacheTypeList
    * @var $_cacheFrontendPool
    * @var \Magento\Framework\App\Http\Context
    */
    protected $_cacheTypeList;
    protected $_cacheFrontendPool;
    protected $httpContext;

    /**
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool
     * @param \Magento\Framework\App\Http\Context $httpContext
	 */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool,
        \Magento\Framework\App\Http\Context $httpContext
        )
    {
        parent::__construct($context);
        $this->httpContext = $httpContext;
        $this->_cacheTypeList = $cacheTypeList;
        $this->_cacheFrontendPool = $cacheFrontendPool;
    }
    
    public function isLoggedIn()
    {
        $isLoggedIn = $this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
        return $isLoggedIn;
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
    public function test()
    {
        return "test";
    }
}