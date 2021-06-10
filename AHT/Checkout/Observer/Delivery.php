<?php

namespace AHT\Checkout\Observer;

class Delivery implements \Magento\Framework\Event\ObserverInterface
{
	// protected $_request;

	// public function __construct(
	// 	\Magento\Framework\App\RequestInterface $request,
	// 	\Psr\Log\LoggerInterface $logger,
	// 	\Magento\Sales\Model\Order\Status\HistoryFactory $historyFactory,
	// 	\Magento\Sales\Model\OrderFactory $orderFactory
	// ) { 
    // $this->_request = $request;
    // $this->_logger = $logger;
    // $this->_historyFactory = $historyFactory;
    // $this->_orderFactory = $orderFactory;
	/**
     * @param \Magento\Checkout\Model\Session
     */
    private $_checkoutSession;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession
    )
    {
        $this->_checkoutSession = $checkoutSession;
    }

	public function execute(\Magento\Framework\Event\Observer $observer)
	{
		try {
            $date = $this->_checkoutSession->getDate();
			$comment = $this->_checkoutSession->getComment();
            $order = $observer->getEvent()->getOrder();
            $order->setData('delivery_comment', $comment);
			$order->setData('delivery_date', $date);
            $order->save();
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
	}
}