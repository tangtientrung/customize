<?php
 
namespace AHT\FastOrder\Controller\Index;
 
class Search extends \Magento\Framework\App\Action\Action
{
 
    /**
     * @param \Magento\Framework\Serialize\Serializer\Json
     */
    private $_json;

    /**
     * @param \Magento\Framework\Controller\Result\JsonFactory
     */
    private $_jsonFactory;

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    private $_productCollectionFactory;

    /**
     * @param \Magento\Catalog\Helper\Image
     */
    private $_imageHelper;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Serialize\Serializer\Json $json,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Helper\Image $imageHelper
    )
    {
        $this->_json = $json;
        $this->_jsonFactory = $jsonFactory;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_imageHelper = $imageHelper;
        parent::__construct($context);
    }
 
    public function execute()
    {
        $data = $this->getRequest()->getContent();
        $response = $this->_json->unserialize($data);
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $search = $response;
        if($search!="")
        {
            $data = $this->getProduct($search);
        }
        else
        {
            $data = "";
        }
        $resultJson = $this->_jsonFactory->create();
        return $resultJson->setData($data);
        // var_dump($this->getProduct('bag'));
    }
    public function getProduct($search)
    {
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addAttributeToFilter('name', [
            'like' => '%' . $search . '%'
        ]);
        $collection->setPageSize(3); // fetching only 3 products
        foreach ($collection as $value) {
            $value['image_url'] = $this->_imageHelper
                ->init($value, 'product_base_image')
                ->getUrl();
        }

        return array_values($collection->toArray());
    }
}