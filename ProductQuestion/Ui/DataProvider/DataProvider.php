<?php
 
namespace AHT\ProductQuestion\Ui\DataProvider;
 
 
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $_loadedData;
    protected $collection;
 
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \AHT\ProductQuestion\Model\ResourceModel\ProductAnswer\CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    )
    {
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }
}