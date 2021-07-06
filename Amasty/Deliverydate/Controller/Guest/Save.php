<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */

namespace Amasty\Deliverydate\Controller\Guest;

use Amasty\Deliverydate\Model\DeliverydateRepository;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Sales\Model\OrderRepository;
use Psr\Log\LoggerInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class Save extends \Amasty\Deliverydate\Controller\Deliverydate\Save
{
    /**
     * @var \Magento\Sales\Helper\Guest
     */
    private $guestHelper;

    public function __construct(
        Context $context,
        DeliverydateRepository $deliverydateRepository,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        \Magento\Sales\Controller\AbstractController\OrderViewAuthorization $orderAuthorization,
        OrderRepository $orderRepository,
        LoggerInterface $logInterface,
        \Amasty\Deliverydate\Helper\Data $deliveryHelper,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Sales\Helper\Guest $guestHelper
    ) {
        parent::__construct(
            $context,
            $deliverydateRepository,
            $coreRegistry,
            $resultPageFactory,
            $orderAuthorization,
            $orderRepository,
            $logInterface,
            $deliveryHelper,
            $date,
            $transportBuilder
        );
        $this->guestHelper = $guestHelper;
    }

    /**
     * Get Redirect to Order View. For Guest
     *
     * @param int $orderId
     *
     * @return \Magento\Framework\App\ResponseInterface
     */
    protected function getRedirect($orderId)
    {
        return $this->_redirect('sales/guest/view');
    }

    /**
     * @param int $orderId
     *
     * @return \Magento\Sales\Api\Data\OrderInterface
     * @throws NoSuchEntityException
     */
    protected function getOrder($orderId)
    {
        $result = $this->guestHelper->loadValidOrder($this->getRequest());
        if ($result instanceof \Magento\Framework\Controller\ResultInterface) {
            throw new NoSuchEntityException();
        }
        return $this->coreRegistry->registry('current_order');
    }
}
