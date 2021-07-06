<?php
namespace AHT\Register\Block;

class CountryName extends \Magento\Framework\View\Element\Template
{
    /**
     * @param \Magento\Directory\Model\CountryFactory
     */
    protected $_countryFactory;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Directory\Model\CountryFactory $countryFactory,
        array $data = []
       ) 
       {
        $this->_countryFactory = $countryFactory;
        parent::__construct($context, $data);
       }
        
    public function getCountryName($countryCode)
    {    
        $country = $this->_countryFactory->create()->loadByCode($countryCode);
        return $country->getName();
    }
}
