<?php
namespace OmnyfyCustomization\MpcIntegration\Block\Adminhtml\Edit\Tab;

class Field  extends \Magento\Framework\View\Element\Template
{
    /**
     *
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $customerRepositoryInterface;

    /**
     * @param \Magento\Framework\Serialize\Serializer\Json
     */
    private $_json;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param array $data
     */

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
        \Magento\Framework\Serialize\Serializer\Json $json,
        array $data = []
    ) {
        $this->_json = $json;
        $this->customerRepositoryInterface = $customerRepositoryInterface;
        parent::__construct($context, $data);
    }

    public function getUserId()
    {
        return $this->getRequest()->getParam('id');
    }

    public function getConsentAdditional()
    {
        $customer =$this->customerRepositoryInterface->getById($this->getUserId());
        $data = "";
        if($customer->getCustomAttribute('consent_additional'))
        {
            $data = $customer->getCustomAttribute('consent_additional')->getValue();
        }

        if($data!="")
        {
            return $this->_json->unserialize($data);
        }

        return $data;
    }
}
