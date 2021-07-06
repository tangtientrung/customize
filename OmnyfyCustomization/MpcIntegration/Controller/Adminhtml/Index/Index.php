<?php
namespace OmnyfyCustomization\MpcIntegration\Controller\Adminhtml\Index;

class Index extends \Magento\Customer\Controller\Adminhtml\Index
{
    /**
     * Customer compare grid
     *
     * @return \Magento\Framework\View\Result\Layout
     */
    public function execute()
    {

        $this->initCurrentCustomer();
        $resultLayout = $this->resultLayoutFactory->create();
        return $resultLayout;
    }


}
