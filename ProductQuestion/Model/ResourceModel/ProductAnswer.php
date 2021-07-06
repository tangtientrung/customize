<?php
 
namespace AHT\ProductQuestion\Model\ResourceModel;
 
class ProductAnswer extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('product_answer', 'entity_id');
    }
}