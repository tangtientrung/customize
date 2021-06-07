<?php
namespace AHT\Sa\Model\Source;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\OptionFactory;
use Magento\Framework\DB\Ddl\Table;


class SaleAgent extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

    protected $optionFactory;


    /**
     * @param \Magento\Customer\Model\CustomerFactory
     */
    private $_customerFactory;

    public function __construct(
        \Magento\Customer\Model\CustomerFactory $customerFactory
    
    )
    {
        $this->_customerFactory = $customerFactory;
    }
    // public function getAllOptions()
    // {
    //     $this->_options=[ 
    //         ['label'=>'Select Options', 'value'=>''],
    //     ];

    //     $customerFactory= $this->_customerFactory->create()->getCollection()->addFieldToFilter('is_sales_agent',1);
    //     foreach($customerFactory as $c)
    //     {
    //         $text = $c->getLastName().' ' .$c->getFirstName() .' ' .$c->getMiddleName();
    //         $this->_options=[ 
    //         ['label'=>$text, 'value'=>$c->getId()]
            
    //         ];
    //     }
        
    //     return $this->_options;
    // }


    // public function getOptionText($value)
    // {
    //     foreach ($this->getAllOptions() as $option) 
    //     {
    //         if ($option['value'] == $value) {
    //             return $option['label'];
    //         }
    //     }
    //     return false;
    // }
    public function getAllOptions() 
    {
        $type = [];
        $type[] = [
                'value' => '',
                'label' => '--Select--'
            ];
        $customerFactory= $this->_customerFactory->create()->getCollection()->addFieldToFilter('is_sales_agent',1);
        foreach($customerFactory as $c)
        {
            $text = $c->getFirstname() .' ' .$c->getMiddlename().' ' .$c->getLastname();
            $type[] = [
                'value' => $c->getId(),
                'label' => $text
            ];
        }
        
       
        return $type;
    }
    
    public function getOptionText($value) 
    {
        foreach ($this->getAllOptions() as $option) {
            if ($option['value'] == $value) {
                return $option['label'];
            }
        }
        return false;
    }


    
}