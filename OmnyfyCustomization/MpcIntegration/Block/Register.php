<?php
namespace OmnyfyCustomization\MpcIntegration\Block;

class Register extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;
    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        array $data = []
    )
    {
        $this->_scopeConfig = $scopeConfig;
        parent::__construct($context, $data);
    }

    public  function  getFields()
    {
        $consents = $this->_scopeConfig->getValue('omnyfy/omnyfy/consents', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $consents = explode(",",$consents);
        return $consents;
    }
}
