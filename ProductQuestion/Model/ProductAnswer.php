<?php
 
namespace AHT\ProductQuestion\Model;
 
class ProductAnswer extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init('AHT\ProductQuestion\Model\ResourceModel\ProductAnswer');
    }
}