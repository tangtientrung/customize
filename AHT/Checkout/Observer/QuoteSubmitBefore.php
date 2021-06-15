<?php
namespace AHT\Checkout\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

class QuoteSubmitBefore implements ObserverInterface
{
/**
 * @var \Magento\Framework\ObjectManagerInterface
 */
protected $_objectManager;

/**
 * @param \Magento\Framework\ObjectManagerInterface $objectmanager
 */
public function __construct(\Magento\Framework\ObjectManagerInterface $objectmanager)
{
    $this->_objectManager = $objectmanager;
}

public function execute(EventObserver $observer)
{
    $order = $observer->getOrder();
    $quoteRepository = $this->_objectManager->create('Magento\Quote\Model\QuoteRepository');
    /** @var \Magento\Quote\Model\Quote $quote */
    $quote = $quoteRepository->get($order->getQuoteId());
    $order->setDeliveryDate( $quote->getDeliveryDate() );
    $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/templog1.log');
    $logger = new \Zend\Log\Logger();
    $logger->addWriter($writer);
    $logger->info($order->getQuoteId());
    $logger->info($order->getData());
    return $this;
}
}