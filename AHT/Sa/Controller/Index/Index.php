<?php
 
namespace AHT\Sa\Controller\Index;
 
use Magento\Framework\App\Action;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Sales\Model\OrderFactory;
use Magento\Catalog\Model\ProductFactory;

class Index extends \Magento\Framework\App\Action\Action
{
    /**
    * @param \Magento\Sales\Model\OrderFactory
    */
   private $_orderFactory;
   
   /**
    * @param \Magento\Sales\Model\ResourceModel\OrderFactory
    */
   private $_orderResource;

   /**
    * @param \Magento\Catalog\Model\ProductFactory
    */
   private $_productFactory;

   /**
    * @param \Magento\Catalog\Model\ResourceModel\Product
    */
   private $_productResource;

   /**
    * @param \AHT\Sa\Model\SaFactory
    */
   private $_saFactory;

   /**
    * @param \AHT\Sa\Model\ResourceModel\SaFactory
    */
   private $_saResource;
    
    /**
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    private $_collectionFactory;
    /**
     * @param \Magento\Customer\Model\CustomerFactory
     */
    private $_customerFactory;


    /*
    * @param Action\Context $context
    * @param FormFactory $formFactory
    */
    public function __construct(
        // \Magento\Sales\Model\OrderFactory $orderFactory,
        // // \Magento\Sales\Model\ResourceModel\OrderFactory $orderResource,
        // \Magento\Catalog\Model\ProductFactory $productFactory,
        // // \Magento\Catalog\Model\ResourceModel\ProductFactory $productResource,
        // \AHT\Sa\Model\SaFactory $saFactory,
        // // \AHT\Sa\Model\ResourceModel\SaFactory $_saResource,
        
        Action\Context $context,
        // \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory,
        \Magento\Customer\Model\CustomerFactory $customerFactory
    )
    {
        // $this->_collectionFactory = $collectionFactory;
        // // $this->_orderResource = $orderResource;
        // $this->_orderFactory = $orderFactory;
        // // $this->_productResource = $productResource;
        // $this->_productFactory = $productFactory;
        // $this->_saFactory = $saFactory;
        // $this->_saResource= $_saResource;
        $this->_customerFactory = $customerFactory;
        
        parent::__construct($context);
    }
 
    public function execute()
    {
    // /** @var \Magento\Sales\Model\Order $order */
    // $order_id = 39;
    // $order = $this->_orderFactory->create();
    // $orderResource= $this->_orderResource->create()->load($order_id,$order);
    // foreach ($order->getAllItems() as $item) {
    //     $product = $this->_productFactory->create();
    //     $productResource = $this->_productResource->create()->load($item->getProductId(),$product);
    //     if($product->getAttributeText('commission_type'))
    //     {
    //         // $sa = $this->_saFactory->create();
    //         // $sa->setOrderId($order_id);
    //         // $sa->setOrderItemId($item['item_id']);
    //         // $sa->setOrderItemSku($item['sku']);
    //         // $sa->setOrderItemPrice($item['price']*$item['qty_ordered']);
    //         // $sa->setCommissionType($product->getAttributeText('commission_type'));
    //         // $sa->setCommissionValue($product->getData('commission_value'));
    //         // $sa->save();
    //         echo $product->getAttributeText('commission_type');
    //     }
    //     }


    // $collection = $this->_collectionFactory->create();
    // $collection->addAttributeToSelect('*');
    // $collection->addAttributeToFilter('sale_agent_id',  array('notnull' => true));
    // $collection->setPageSize(10); // fetching only 3 products
    // foreach ($collection as $product) {
    //     print_r($product->getSaleAgentId());     
    //     echo "<br>";
    // }
    //     // return $product->getData('sale_agent_id');
    // }
    $customerFactory= $this->_customerFactory->create()->getCollection()->addFieldToFilter('is_sales_agent',1);
    foreach($customerFactory as $c)
    {
        var_dump($c->getFirstname() .' ' .$c->getMiddlename().' ' .$c->getLastname());
       
    }
}
    
}