<?php
namespace AHT\Sa\Plugin\CustomerData;
use Magento\Customer\Helper\Session\CurrentCustomer;

class Customer
{
    protected $currentCustomer;

    public function __construct(
        CurrentCustomer $currentCustomer
    ) {
        $this->currentCustomer = $currentCustomer;
    }

    public function afterGetFirstname(\Magento\Customer\Model\Data\Customer $subject, $result)
    {
        if($subject->getCustomAttribute('is_sales_agent'))
        {
            if($subject->getCustomAttribute('is_sales_agent')->getValue()==1)
            {
                $exit =strpos($result, 'SA:' );
                if($exit===false){
                    $result = 'SA: '.$result;
                }
                
            }
        }
        
        
        return $result;
       
    }
}
