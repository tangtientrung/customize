<?php
namespace AHT\Checkout\Plugin\Quote;

class Delivery
{
    protected $_checkoutSession;

    public function __construct(
              \Magento\Checkout\Model\Session $checkoutSession    
           )
    {
        $this->_checkoutSession = $checkoutSession;
    }

    public function getQuotes()
    {
        return $this->checkoutSession->getQuote();
    }
    public function aroundConvert(
        \Magento\Quote\Model\Quote\Item\ToOrderItem $subject,
        \Closure $proceed,
        \Magento\Quote\Model\Quote\Item\AbstractItem $item,
        $additional = []
    ) {
        /** @var $orderItem Item */
        $orderItem = $proceed($item, $additional);
        
        
       
        $allItems = $this->_checkoutSession->getQuote()->getAllItems();
        foreach ($allItems as $item) {
            $delivery_date = $item->getDeliveryDate();
        }
        $orderItem->setDeliveryDate('2021-06-12');
        $orderItem->setDeliveryComment($item->getDeliveryComment());
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/templog1.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info($delivery_date);
        $logger->info($orderItem->getData());
        
        return $orderItem;
    }
    
}
