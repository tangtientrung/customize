<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */


namespace Amasty\Deliverydate\Controller\Adminhtml;

use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Action\Context;
use Psr\Log\LoggerInterface;
use Magento\Backend\Model\Session;
use Magento\Ui\Component\MassAction\Filter;

abstract class Tinterval extends \Magento\Backend\App\Action
{

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;
    /**
     * @var LoggerInterface
     */
    protected $logInterface;
    /**
     * @var Session
     */
    protected $session;
    /**
     * @var \Amasty\Deliverydate\Model\ResourceModel\Tinterval
     */
    protected $resourceModel;
    /**
     * @var \Amasty\Deliverydate\Model\ResourceModel\Tinterval\Collection
     */
    protected $tintervalCollection;
    /**
     * @var Filter
     */
    protected $filter;
    /**
     * @var \Amasty\Deliverydate\Model\Tinterval
     */
    protected $model;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;
    /**
     * @var \Amasty\Deliverydate\Helper\Data
     */
    protected $deliveryHelper;

    /**
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Magento\Framework\Registry $coreRegistry,
        LoggerInterface $logInterface,
        \Amasty\Deliverydate\Model\ResourceModel\Tinterval $resourceModel,
        \Amasty\Deliverydate\Model\TintervalFactory $model,
        \Amasty\Deliverydate\Model\ResourceModel\Tinterval\Collection $tintervalCollection,
        Filter $filter,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Amasty\Deliverydate\Helper\Data $deliveryHelper
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $coreRegistry;
        $this->logInterface = $logInterface;
        $this->session = $context->getSession();
        $this->resourceModel = $resourceModel;
        $this->tintervalCollection = $tintervalCollection;
        $this->filter = $filter;
        $this->model = $model;
        $this->storeManager = $storeManager;
        $this->date = $date;
        $this->deliveryHelper = $deliveryHelper;
    }

    protected function _initAction()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Amasty_Deliverydate::deliverydate_tinterval');
        $resultPage->addBreadcrumb(__('Manage Time Intervals'), __('Manage Time Intervals'));
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Time Intervals'));

        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Amasty_Deliverydate::deliverydate_tinterval');
    }
}
