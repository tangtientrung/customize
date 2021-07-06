<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */


namespace Amasty\Deliverydate\Controller\Adminhtml;

use Magento\Backend\Model\Session;
use Magento\Framework\View\Result\PageFactory;

abstract class Deliverydate extends \Magento\Backend\App\Action
{

    /**
     * @var \Amasty\Deliverydate\Model\ResourceModel\Deliverydate
     */
    protected $resourceModel;
    /**
     * @var \Amasty\Deliverydate\Model\DeliverydateFactory
     */
    protected $model;
    /**
     * @var \Amasty\Deliverydate\Model\ResourceModel\Deliverydate\Collection
     */
    protected $deliverydateCollection;

    /**
     * @var Session
     */
    protected $session;
    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $orderFactory;
    /**
     * @var \Magento\Sales\Model\ResourceModel\Order
     */
    protected $orderResource;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logInterface;
    /**
     * @var \Amasty\Deliverydate\Helper\Data
     */
    protected $deliveryHelper;
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;
    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $transportBuilder;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Amasty\Deliverydate\Model\ResourceModel\Deliverydate $resourceModel,
        \Amasty\Deliverydate\Model\DeliverydateFactory $model,
        \Amasty\Deliverydate\Model\ResourceModel\Deliverydate\Collection $deliverydateCollection,
        \Magento\Framework\Registry $coreRegistry,
        PageFactory $resultPageFactory,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Sales\Model\ResourceModel\Order $orderResource,
        \Psr\Log\LoggerInterface $logInterface,
        \Amasty\Deliverydate\Helper\Data $deliveryHelper,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
    )
    {
        parent::__construct($context);
        $this->resourceModel = $resourceModel;
        $this->model = $model;
        $this->deliverydateCollection = $deliverydateCollection;
        $this->session = $context->getSession();
        $this->coreRegistry = $coreRegistry;
        $this->resultPageFactory = $resultPageFactory;
        $this->orderFactory = $orderFactory;
        $this->orderResource = $orderResource;
        $this->logInterface = $logInterface;
        $this->deliveryHelper = $deliveryHelper;
        $this->date = $date;
        $this->transportBuilder = $transportBuilder;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Amasty_Deliverydate::deliverydate_deliverydate');
    }
}
