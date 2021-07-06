<?php
 
namespace AHT\Question\Model\ResourceModel\Question;
 
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /*
    * @var $_idFieldName
    */
    protected $_idFieldName = 'id';
 
    protected function _construct()
    {
        $this->_init('AHT\Question\Model\Question', 'AHT\Question\Model\ResourceModel\Question');
    }
}