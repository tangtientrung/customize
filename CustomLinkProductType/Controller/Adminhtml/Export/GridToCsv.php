<?php


namespace AHT\CustomLinkProductType\Controller\Adminhtml\Export;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ResponseInterface;
use Magento\Store\Model\Store;
use Magento\Framework\App\ResourceConnection;

class GridToCsv extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    protected $fileFactory;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $productFactory;


    /**
     * @var \Magento\Framework\File\Csv
     */
    protected $csvProcessor;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $directoryList;

    /**
     * Product collection
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    protected $collection;

    /**
     * @param \Magento\Framework\Registry $registry
     */
    private $_registry;

    /**
     * @param \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepositoryInterface;

    /**
     * @param \Psr\Log\LoggerInterface
     */
    private $logger;

    protected $resourceConnection;

    /**
     * @param Context $context
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
     * @param \Magento\Framework\File\Csv $csvProcessor
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Framework\File\Csv $csvProcessor,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepositoryInterface,
        \Psr\Log\LoggerInterface $logger,
        ResourceConnection $resourceConnection
    ) {

        $this->fileFactory = $fileFactory;
        $this->productFactory = $productFactory;
        $this->csvProcessor = $csvProcessor;
        $this->directoryList = $directoryList;
        parent::__construct($context);
        $this->collection = $collectionFactory;
        $this->_registry = $registry;
        $this->productRepositoryInterface = $productRepositoryInterface;
        $this->logger = $logger;
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * CSV Create and Download
     *
     * @return ResponseInterface
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        $data = $this->getRequest()->getParams();

        /** header name */
        $content[] = [
            'entity_id' => __('Entity ID'),
            'name'=> __('Name'),
            'attribute_set_id' => __('Attribute Set ID'),
            'type_id' => __('Type ID'),
            'sku' => __('Sku'),
            'price' => __('Price'),
            'required_options' => __('Required Options'),
            'visibility' => __('Visibility'),
            'store_id' => __('Store id'),
            'mounting_skus' => __('Mounting product')
        ];

        $collection = $this->collection->create();
        $collection->addAttributeToSelect('*');

        if($data['selected']!="false")
        {
            $collection->addAttributeToFilter('entity_id',array('in' => $data['selected']));
        }
        $connection = $this->resourceConnection->getConnection();
        // $table is table name
        $table = $connection->getTableName('catalog_product_link');

        foreach ($collection as $product)
        {
            $query = "SELECT * FROM `" . $table . "` WHERE product_id = ".$product->getEntityId()." AND link_type_id = '10'";
            $result = $connection->fetchAll($query);

            $skuMounting = "";
            foreach ($result as $r)
            {
                $productSku = $this->productRepositoryInterface->getById($r['linked_product_id']);
                $skuMounting.= $productSku->getSku().',';
            }
            $skuMounting = trim($skuMounting,',');

            $content[] = [
                $product->getEntityId(),
                $product->getName(),
                $product->getAttributeSetId(),
                $product->getTypeId(),
                $product->getSku(),
                $product->getPrice(),
                $product->getRequiredOptions(),
                $product->getVisibility(),
                $product->getStoreId(),
//                implode(',',$this->getMountingProductsList($product->getEntityId()))
                $skuMounting
            ];
        }

        $fileName = 'product.csv'; // Add Your CSV File name

        $filePath =  $this->directoryList->getPath(DirectoryList::MEDIA) . "/" . $fileName;

        $this->csvProcessor->setEnclosure('"')->setDelimiter(',')->saveData($filePath, $content);
        return $this->fileFactory->create(
            $fileName,
            [
                'type'  => "filename",
                'value' => $fileName,
                'rm'    => true, // True => File will be remove from directory after download.
            ],
            DirectoryList::MEDIA,
            'text/csv',
            null
        );
    }
    /**
     * get related products
     *
     * @return array
     */
    public function getMountingProductsList($productId)
    {
        $relatedProduct = [];
        try {
            $product = $this->productRepositoryInterface->getById($productId);
            $related = $product->getRelatedProducts();
//            $related = $product->getMountingProducts();

            if (!empty($related)) {
                foreach ($related as $item) {
                    $relatedProduct[] = $item->getId();
                }
            }
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }

        return $relatedProduct;
    }
}
