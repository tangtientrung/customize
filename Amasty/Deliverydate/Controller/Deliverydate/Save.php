<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */

namespace Amasty\Deliverydate\Controller\Deliverydate;

use Amasty\Deliverydate\Model\DeliverydateRepository;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Sales\Controller\AbstractController\OrderViewAuthorization;
use Magento\Sales\Model\OrderRepository;
use Psr\Log\LoggerInterface;
use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Framework\App\Action\Action
{
    /**
     * @var DeliverydateRepository
     */
    private $deliverydateRepository;

    /**
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * @var LoggerInterface
     */
    private $logInterface;

    /**
     * @var \Amasty\Deliverydate\Helper\Data
     */
    private $deliveryHelper;

    /**
     * @var OrderViewAuthorization
     */
    private $orderAuthorization;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    private $date;

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $transportBuilder;

    /**
     * Save constructor.
     *
     * @param Context                                           $context
     * @param DeliverydateRepository                            $deliverydateRepository
     * @param Registry                                          $coreRegistry
     * @param PageFactory                                       $resultPageFactory
     * @param OrderViewAuthorization                            $orderAuthorization
     * @param OrderRepository                                   $orderRepository
     * @param LoggerInterface                                   $logInterface
     * @param \Amasty\Deliverydate\Helper\Data                  $deliveryHelper
     * @param \Magento\Framework\Stdlib\DateTime\DateTime       $date
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     */
    public function __construct(
        Context $context,
        DeliverydateRepository $deliverydateRepository,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        OrderViewAuthorization $orderAuthorization,
        OrderRepository $orderRepository,
        LoggerInterface $logInterface,
        \Amasty\Deliverydate\Helper\Data $deliveryHelper,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
    ) {
        parent::__construct($context);
        $this->deliverydateRepository = $deliverydateRepository;
        $this->coreRegistry = $coreRegistry;
        $this->resultPageFactory = $resultPageFactory;
        $this->orderRepository = $orderRepository;
        $this->logInterface = $logInterface;
        $this->deliveryHelper = $deliveryHelper;
        $this->orderAuthorization = $orderAuthorization;
        $this->date = $date;
        $this->transportBuilder = $transportBuilder;
    }

    /**
     * Save Delivery Date Action for Customer
     * Can be used by Guest @see \Amasty\Deliverydate\Controller\Guest\Save
     *
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function execute()
    {
        $orderId = $this->getRequest()->getParam('order_id');
        if (!$orderId) {
            return $this->_forward('noroute');
        }
        try {
            $order = $this->getOrder($orderId);
            $deliverydate = $this->deliverydateRepository->getByOrder($orderId);
        } catch (NoSuchEntityException $e) {
            return $this->getRedirect($orderId);
        }

        if (!$deliverydate->isCanEditOnFront()) {
            return $this->getRedirect($orderId);
        }
        try {
            /* get Delivery Date value before save for compare */
            $wasDate = $deliverydate->getDate();

            if (!$deliverydate->prepareForSave($this->getRequest()->getPostValue(), $order)) {
                $this->messageManager->addErrorMessage(__('No data to save'));
                return $this->_redirect('*/*/edit', ['order_id' => $orderId]);
            }
            $deliverydate->validate($order);
            $this->deliverydateRepository->save($deliverydate);
            if ($wasDate != $deliverydate->getDate()) {
                $this->sendNotification($wasDate, $deliverydate, $order);
            }
            $this->messageManager->addSuccessMessage(__('Delivery Date has been successfully saved'));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $this->_redirect('*/*/edit', ['order_id' => $orderId]);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Something went wrong while saving data.'));
            $this->logInterface->critical($e);
        }
        return $this->getRedirect($orderId);
    }

    /**
     * Get Redirect to Order View. For Customer
     *
     * @param int $orderId
     *
     * @return \Magento\Framework\App\ResponseInterface
     */
    protected function getRedirect($orderId)
    {
        return $this->_redirect('sales/order/view', ['order_id' => $orderId]);
    }

    /**
     * @param int $orderId
     *
     * @return \Magento\Sales\Api\Data\OrderInterface
     * @throws NoSuchEntityException
     */
    protected function getOrder($orderId)
    {
        $order = $this->orderRepository->get($orderId);
        if (!$this->orderAuthorization->canView($order)) {
            throw new NoSuchEntityException();
        }
        return $order;
    }

    /**
     * Send Email notification to admin
     *
     * @param string $wasDate
     * @param \Amasty\Deliverydate\Model\Deliverydate $deliverydate
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     */
    protected function sendNotification($wasDate, $deliverydate, $order)
    {
        $recipientEmails = explode(',', $this->deliveryHelper->getStoreScopeValue('editable/email'));
        $recipientEmails = array_filter($recipientEmails);

        if (empty($recipientEmails)) {
            return;
        }
        $value = $deliverydate->getFormattedDate();
        $deliverydate->setDate($value);

        if ($order->getCustomerIsGuest()) {
            $customerName = $order->getBillingAddress()->getName();
        } else {
            $customerName = $order->getCustomerName();
        }

        $sender = ['email' => $order->getCustomerEmail(), 'name' => $customerName];

        $vars = [
            'delivery' => $deliverydate,
            'was_date' => $this->deliveryHelper->convertDateOutput($wasDate),
            'order' => $order
        ];

        $this->transportBuilder
            ->setTemplateIdentifier('amdeliverydate_admin_email_template')
            ->setTemplateOptions(['area' => 'adminhtml', 'store' => 0])
            ->setTemplateVars($vars)
            ->setFrom($sender)
            ->addTo($recipientEmails);

        $transport = $this->transportBuilder->getTransport();
        $transport->sendMessage();
    }
}
