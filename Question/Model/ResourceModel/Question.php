<?php
 
namespace AHT\Question\Model\ResourceModel;
 
class Question extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('question', 'id');
    }
}