<?php
 
namespace AHT\ProductQuestion\Model\ResourceModel\ProductAnswer;
 
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /*
    * @var $_idFieldName
    */
    protected $_idFieldName = 'entity_id';
 
    protected function _construct()
    {
        $this->_init('AHT\ProductQuestion\Model\ProductAnswer', 'AHT\ProductQuestion\Model\ResourceModel\ProductAnswer');
    }
}