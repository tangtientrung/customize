<?php
namespace AHT\Wholesale\Controller\Adminhtml\Order;

use Magento\Framework\Controller\ResultFactory; 

class UpdateQty extends \Magento\Backend\App\Action 
{
    const ADMIN_RESOURCE = 'Magento_Sale::edit';

    const PAGE_TITLE = 'Page Title';

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_pageFactory;

    /**
     * @param \Magento\Sales\Model\OrderFactory
     */
    private $_orderFactory;

    /**
     * @param \Magento\InventoryReservations\Model\ReservationFactory
     */
    private $_reservationFactory;

    /**
     * @param Magento\Framework\App\ResourceConnection
     */
    private $_resourceConnection;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
       \Magento\Backend\App\Action\Context $context,
       \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magento\InventoryReservations\Model\ResourceModel\GetReservationsQuantity $getReservationsQuantity
    )
    {
        $this->_pageFactory = $pageFactory;
        $this->_orderFactory = $orderFactory;
        $this->_resourceConnection = $resourceConnection;
        $this->_getReservationsQuantity = $getReservationsQuantity;
        return parent::__construct($context);
    }

    /**
     * Index action
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        
        $connection = $this->_resourceConnection->getConnection();

        $data = $this->getRequest()->getParams();
        $order = $this->_orderFactory->create()->load($data['order_id']);
        $i = 0;
        $sub_total = 0;
        foreach ($order->getAllItems() as $item) {
            $item->setQtyOrdered($data['qty'][$i]);
            $item->setRowTotal($data['qty'][$i]*$item->getOriginalPrice());
            $sub_total += $data['qty'][$i]*$item->getOriginalPrice();
            $item->save();
            

            //update qty order in inventory_reservation table
            // $table is table name
            $table = $connection->getTableName('inventory_reservation');

            //For Select query
            $sku = $item->getsku();
            $sku = '"'.$sku.'"';
            
            $query = "Select * FROM " . $table." Where sku = ".$sku;
            $result = $connection->fetchAll($query);

            foreach($result as $item1)
            {
                $metadata = $item1['metadata'];
                $metadata = explode(",",$metadata);
                $metadata = explode(":",$metadata[2]);
                $metadata = explode("\"",$metadata[1]);
                $order_id = $metadata[1];
                if($order_id == $data['order_id'] && $item1['sku'] == $item->getSku() )
                {
                    $qty = -$data['qty'][$i];
                    $query = "UPDATE `" . $table . "` SET `quantity`= ". $qty." WHERE reservation_id =  ".$item1['reservation_id'];
                    $connection->query($query);
                }
                
            }
            $i++;
       
        }
        $order->setSubtotal($sub_total);
        $order->setGrandTotal($sub_total+$order->getTaxAmount()+$order->getShippingAmount()+$order->getDiscountAmount());
        $order->setBaseGrandTotal($sub_total+$order->getBaseTaxAmount()+$order->getBaseShippingAmount()+$order->getBaseDiscountAmount());
        $order->save();
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        // Your code

        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;
    }

    /**
     * Is the user allowed to view the page.
    *
    * @return bool
    */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(static::ADMIN_RESOURCE);
    }

    
}
