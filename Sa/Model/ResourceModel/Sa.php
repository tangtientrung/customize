<?php
 
namespace AHT\Sa\Model\ResourceModel;
 
class Sa extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('sa', 'entty_id');
    }
}