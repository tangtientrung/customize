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

class Edit extends \Magento\Framework\App\Action\Action
{
    /**
     * @var DeliverydateRepository
     */
    private $deliverydateRepository;

    /**
     * @var Registry
     */
    private $coreRegistry;

    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * @var \Amasty\Deliverydate\Helper\Data
     */
    private $deliveryHelper;

    /**
     * @var \Magento\Sales\Helper\Guest
     */
    private $guestHelper;

    /**
     * Edit constructor.
     *
     * @param Context                          $context
     * @param DeliverydateRepository           $deliverydateRepository
     * @param Registry                         $coreRegistry
     * @param PageFactory                      $resultPageFactory
     * @param \Amasty\Deliverydate\Helper\Data $deliveryHelper
     * @param \Magento\Sales\Helper\Guest      $guestHelper
     */
    public function __construct(
        Context $context,
        DeliverydateRepository $deliverydateRepository,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        \Amasty\Deliverydate\Helper\Data $deliveryHelper,
        \Magento\Sales\Helper\Guest $guestHelper
    ) {
        parent::__construct($context);
        $this->deliverydateRepository = $deliverydateRepository;
        $this->coreRegistry = $coreRegistry;
        $this->resultPageFactory = $resultPageFactory;
        $this->deliveryHelper = $deliveryHelper;
        $this->guestHelper = $guestHelper;
    }

    public function execute()
    {
        $orderId = $this->getRequest()->getParam('order_id');
        if (!$orderId) {
            return $this->_forward('noroute');
        }

        /* load order to register by params from session for guest */
        $result = $this->guestHelper->loadValidOrder($this->getRequest());
        if ($result instanceof \Magento\Framework\Controller\ResultInterface) {
            return $result;
        }
        try {
            $deliverydate = $this->deliverydateRepository->getByOrder($orderId);
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            return $this->_redirect('sales/guest/view');
        }

        if (!$deliverydate->isCanEditOnFront()) {
            return $this->_redirect('sales/guest/view');
        }

        $this->coreRegistry->register('current_amasty_deliverydate', $deliverydate);
        $order = $this->coreRegistry->registry('current_order');

        $title = __('Edit Delivery Date For The Order #%1', $order->getIncrementId());
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend($title);
        $this->guestHelper->getBreadcrumbs($resultPage);
        $breadcrumbs = $resultPage->getLayout()->getBlock('breadcrumbs');
        $breadcrumbs->addCrumb(
            'cms_page',
            [
                'label' => __('Order Information'),
                'title' => __('Order Information'),
                'link'  => $this->_url->getUrl('sales/guest/view')
            ]
        );
        $breadcrumbs->addCrumb(
            'delivery_date',
            ['label' => __('Delivery Date'), 'title' => $title]
        );

        return $resultPage;
    }
}
