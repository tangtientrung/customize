<?php
 
namespace AHT\Question\Ui\DataProvider;
 
use AHT\Question\Model\QuestionFactory;
use AHT\Question\Model\ResourceModel\Question\CollectionFactory;
 
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $_loadedData;
    protected $collection;
 
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    )
    {
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }
 
    public function getData()
    {
        if (isset($this->_loadedData)) {
            return $this->_loadedData;
        }
        $this->collection->getSelect()
        ->joinLeft('catalog_product_entity_varchar as pro','main_table.product_id = pro.entity_id AND pro.attribute_id = 73 ',array('*'))
        ->joinLeft('customer_entity as user','main_table.user_id = user.entity_id',array('*'));
        $items = $this->collection->getItems();
        foreach ($items as $block) {
        $this->_loadedData[$block->getId()] = $block->getData();

        }
        return  $this->_loadedData;
    }
}