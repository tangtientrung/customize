<?php

namespace AHT\ProductQuestion\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @param \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $_transportBuilder;

    /**
     * @param \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @param \Magento\Framework\Translate\Inline\StateInterface
     */
    protected $_inlineTranslation;
    /**
        * @var \Magento\Framework\App\Config\ScopeConfigInterface
    */
    protected $_scopeConfig;

    /**
     * @param \Magento\Framework\App\Cache\TypeListInterface
     */
    private $_cacheTypeList;

    /**
     * @param \Magento\Framework\App\Cache\Frontend\Pool
     */
    private $_cacheFrontendPool;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Translate\Inline\StateInterface $state,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool
    )
    {
        $this->_transportBuilder = $transportBuilder;
        $this->_storeManager = $storeManager;
        $this->_inlineTranslation = $state;
        $this->_scopeConfig = $scopeConfig;
        $this->_cacheTypeList = $cacheTypeList;
        $this->_cacheFrontendPool = $cacheFrontendPool;
        parent::__construct($context);
    }

    public function flushCache()
    {
        $types = array('config','layout','block_html','collections','reflection','db_ddl','eav','config_integration','config_integration_api','full_page','translate','config_webservice');
    
        foreach ($types as $type) {
            $this->_cacheTypeList->cleanType($type);
        }
        foreach ($this->_cacheFrontendPool as $cacheFrontend) {
            $cacheFrontend->getBackend()->clean();
        }
    }
    public function sendEmail($templateId,$fromEmail,$fromName,$toEmail,$question,$answer,$name)
    {
        try {
            // template variables pass here
            $template = $this->_scopeConfig->getValue('aht/product/email_template', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $templateVars = [
                'question' => $question,
                'answer' => $answer,
                'name' => $name
            ]; 

            $storeId = $this->_storeManager->getStore()->getId();

            $from = ['email' => $fromEmail, 'name' => $fromName];
            $this->_inlineTranslation->suspend();

            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
            $templateOptions = [
                'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                'store' => $storeId
            ];
            $transport = $this->_transportBuilder->setTemplateIdentifier($templateId, $storeScope)
                ->setTemplateOptions($templateOptions)
                ->setTemplateVars($templateVars)
                ->setFrom($from)
                ->addTo($toEmail)
                ->getTransport();
            $transport->sendMessage();
            $this->_inlineTranslation->resume();
        } catch (\Exception $e) {
            $this->_logger->info($e->getMessage());
        }
    }
    
}