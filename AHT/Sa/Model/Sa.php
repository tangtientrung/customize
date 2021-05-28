<?php
 
namespace AHT\Sa\Model;
 
class Sa extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init('AHT\Sa\Model\ResourceModel\Sa');
    }
}