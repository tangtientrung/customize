<?php
 
namespace AHT\ProductQuestion\Model\ResourceModel;
 
class ProductQuestion extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('product_question', 'entity_id');
    }
}