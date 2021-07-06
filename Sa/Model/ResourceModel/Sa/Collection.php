<?php
 
namespace AHT\Sa\Model\ResourceModel\Sa;
 
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /*
    * @var $_idFieldName
    */
    protected $_idFieldName = 'entity_id';
 
    protected function _construct()
    {
        $this->_init('AHT\Sa\Model\Sa', 'AHT\Sa\Model\ResourceModel\Sa');
    }
}