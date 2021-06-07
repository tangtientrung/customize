<?php
namespace AHT\Register\Model\Source;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\OptionFactory;
use Magento\Framework\DB\Ddl\Table;


class CompanyType extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
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
    public function getAllOptions() 
    {
        $type = [];
        $type[] = [
                'value' => '',
                'label' => '--Select--'
            ];
        $type[] = [
            'value' => '1',
            'label' => 'CBD Manufacturer'
        ];
        $type[] = [
            'value' => '2',
            'label' => 'CBD Brand and Marketing company'
        ];
        $type[] = [
            'value' => '3',
            'label' => 'CBD Extractor'
        ];
        $type[] = [
            'value' => '4',
            'label' => 'Other'
        ];
        
       
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