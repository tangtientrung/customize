<?php

namespace OmnyfyCustomization\MpcIntegration\Observer;

use Magento\Framework\Event\Observer;

class Save implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * @param \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $customerRepository;
    /**
     * @param \Magento\Framework\Controller\Result\JsonFactory
     */
    private $_jsonFactory;


    public function __construct(

        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
    )

    {
        $this->customerRepository = $customerRepository;
        $this->_jsonFactory = $jsonFactory;
    }

    public function execute(Observer $observer)
    {
        $accountController = $observer->getAccountController();
        $customer = $observer->getCustomer();
        $request = $accountController->getRequest();
        $data = $request->getPostValue();
        $json = array();
        $arr = array("form_key","success_url","error_url","firstname","lastname","email","password","password_confirmation");
        foreach ($data as $key => $val)
        {
            if(!in_array($key,$arr))
            {
                array_add($json,$key,$val);
            }
        }
        $json = json_encode($json);

        $customer = $observer->getEvent()->getData('customer');

        $customer->setCustomAttribute('consent_additional', $json);
        $this->customerRepository->save($customer);
    }

}
