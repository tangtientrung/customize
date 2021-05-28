<?php
 
namespace AHT\Sa\Observer;
 
use Magento\Framework\Event\Observer;
 
class Save implements \Magento\Framework\Event\ObserverInterface
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

    

    

    public function __construct(
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Sales\Model\ResourceModel\Order $orderResource,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Model\ResourceModel\Product $productResource,
        \AHT\Sa\Model\SaFactory $saFactory,
        \AHT\Sa\Model\ResourceModel\Sa $_saResource
        )
    {
        $this->_orderResource = $orderResource;
        $this->_orderFactory = $orderFactory;
        $this->_productResource = $productResource;
        $this->_productFactory = $productFactory;
        $this->_saFactory = $saFactory;
        $this->_saResource= $_saResource;
    }
    public function execute(Observer $observer)
    {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $observer->getEvent()->getData('order');
        $order_id = $order->getId();
        $order = $this->_orderFactory->create()->load($order_id);
        // $this->_orderResource->load($order_id,$order);
        foreach ($order->getAllItems() as $item) {
            $product = $this->_productFactory->create()->load($item->getProductId());
            if($product->getAttributeText('commission_type'))
            {
                $sa = $this->_saFactory->create();
                $sa->setOrderId($order_id);
                $sa->setOrderItemId($item['item_id']);
                $sa->setOrderItemSku($item['sku']);
                $sa->setOrderItemPrice($item['price']*$item['qty_ordered']);
                $sa->setCommissionType($product->getAttributeText('commission_type'));
                $sa->setCommissionValue($product->getData('commission_value'));
                $sa->save();
            }
        }
        
    }
   
}