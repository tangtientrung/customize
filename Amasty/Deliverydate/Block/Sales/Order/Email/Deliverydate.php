<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */

namespace Amasty\Deliverydate\Block\Sales\Order\Email;

use Magento\Framework\View\Element\Template\Context;
use Amasty\Deliverydate\Model\DeliverydateFactory;

class Deliverydate extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Amasty\Deliverydate\Model\ResourceModel\Deliverydate
     */
    protected $deliverydateResourceModel;

    /**
     * @var DeliverydateFactory
     */
    protected $deliveryDateFactory;

    /**
     * @var \Amasty\Deliverydate\Helper\Data
     */
    protected $deliveryHelper;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * @var \Magento\Sales\Model\OrderRepository
     */
    protected $orderRepository;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    public function __construct(
        Context $context,
        \Amasty\Deliverydate\Model\ResourceModel\Deliverydate $deliverydateResourceModel,
        DeliverydateFactory $deliveryDateFactory,
        \Amasty\Deliverydate\Helper\Data $deliveryHelper,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Sales\Model\OrderRepository $orderRepository,
        \Magento\Customer\Model\Session $customerSession,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->deliverydateResourceModel = $deliverydateResourceModel;
        $this->deliveryDateFactory = $deliveryDateFactory;
        $this->deliveryHelper = $deliveryHelper;
        $this->date = $date;
        $this->coreRegistry = $coreRegistry;
        $this->orderRepository = $orderRepository;
        $this->customerSession = $customerSession;
    }

    protected function _construct()
    {
        parent::_construct();

        $this->setTemplate('Amasty_Deliverydate::email.phtml');
    }

    /**
     * @return \Amasty\Deliverydate\Model\Deliverydate
     */
    public function getDeliveryDate()
    {
        if ($this->getData('delivery_date') === null) {
            $orderId = $this->getData('order_id');
            /** @var \Amasty\Deliverydate\Model\Deliverydate $deliveryDate */
            $deliveryDate = $this->deliveryDateFactory->create();
            $this->deliverydateResourceModel->load($deliveryDate, $orderId, 'order_id');

            $this->setData('delivery_date', $deliveryDate);
        }

        return $this->getData('delivery_date');
    }

    /**
     * @return \Magento\Sales\Api\Data\OrderInterface
     */
    public function getOrder()
    {
        return $this->orderRepository->get($this->getOrderId());
    }

    public function getFields()
    {
        $fields = $this->getData('fields');
        return $fields;
    }

    /**
     * @return int
     */
    public function getOrderId()
    {
        return $this->getData('order_id');
    }

    /**
     * Before rendering html, but after trying to load cache.
     * Prepare variables for output
     *
     * @return $this
     *
     */
    protected function _beforeToHtml()
    {
        $fields = $this->getFields();
        if (is_array($fields) && !empty($fields)) {
            $deliveryDate = $this->getDeliveryDate();
            $label = '';
            $list = [];
            foreach ($fields as $field) {
                $value = $deliveryDate->getData($field);
                if (!$value) {
                    continue;
                }

                switch ($field) {
                    case 'date':
                        $label = __('Delivery Date') . ':';
                        $value = $deliveryDate->getFormattedDate();
                        break;
                    case 'time':
                        $label = __('Delivery Time Interval') . ':';
                        break;
                    case 'comment':
                        $label = __('Delivery Comments') . ':';
                        break;
                }

                $list[$field] = ['label' => $label, 'value' => $value];
            }
            $this->assign('list', $list);
        }

        return parent::_beforeToHtml();
    }
}
