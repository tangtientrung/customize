<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */

namespace Amasty\Deliverydate\Block\Adminhtml\Sales\Order\View;

use Amasty\Deliverydate\Model\DeliverydateFactory;
use Magento\Framework\View\Element\Template\Context;

class Deliverydate extends \Magento\Framework\View\Element\Template
{

    /**
     * @var DeliverydateFactory
     */
    protected $deliveryDateFactory;
    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;
    /**
     * @var \Amasty\Deliverydate\Helper\Data
     */
    protected $deliveryHelper;
    /**
     * @var \Amasty\Deliverydate\Model\ResourceModel\Deliverydate
     */
    protected $deliverydateResourceModel;
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;

    public function __construct(
        Context $context,
        DeliverydateFactory $deliveryDateFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Amasty\Deliverydate\Helper\Data $deliveryHelper,
        \Amasty\Deliverydate\Model\ResourceModel\Deliverydate $deliverydateResourceModel,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        array $data = []
    ) {
        $this->deliveryDateFactory = $deliveryDateFactory;
        $this->coreRegistry = $coreRegistry;
        $this->deliveryHelper = $deliveryHelper;
        $this->deliverydateResourceModel = $deliverydateResourceModel;
        $this->date = $date;

        parent::__construct($context, $data);
    }

    protected function _construct()
    {
        parent::_construct();

        $this->setTemplate('Amasty_Deliverydate::delivery_view.phtml');
        $deliveryDate = $this->deliveryDateFactory->create();
        $this->deliverydateResourceModel->load($deliveryDate, $this->_getOrderId(), 'order_id');
        $this->coreRegistry->register('current_deliverydate', $deliveryDate);
    }

    protected function _getOrderId()
    {
        if ($this->coreRegistry->registry('current_order')) {
            return $this->coreRegistry->registry('current_order')->getId();
        }
        if ($this->coreRegistry->registry('current_invoice')) {
            return $this->coreRegistry->registry('current_invoice')->getOrderId();
        }
        if ($this->coreRegistry->registry('current_shipment')) {
            return $this->coreRegistry->registry('current_shipment')->getOrderId();
        }
    }

    public function isOrderViewPage() {
        if ($this->coreRegistry->registry('current_order')) {
            return true;
        }
        return false;
    }

    public function getDeliveryDateFields($place = 'order')
    {
        $fields = array();
        if ($this->coreRegistry->registry('current_deliverydate_place')) {
            $place = $this->coreRegistry->registry('current_deliverydate_place');
        }
        $deliveryDate = $this->coreRegistry->registry('current_deliverydate');
        $show = $this->deliveryHelper->whatShow($place . '_view');
        foreach ($show as $key) {
            switch ($key) {
                case 'date':
                    if (!$deliveryDate['date']
                        || '0000-00-00' == $deliveryDate['date']
                        || '1970-01-01' == $deliveryDate['date']) {
                        $date = '';
                    } else {
                        $date = $this->date->date(
                            $this->deliveryHelper->getPhpFormat(),
                            $deliveryDate['date']
                        );
                    }
                    $fields[] = array('code' => 'date',
                        'label' => __('Delivery Date'),
                        'value' => $date
                    );
                    break;
                case 'time':
                    $fields[] = array('code' => 'time',
                        'label' => __('Delivery Time Interval'),
                        'value' => $deliveryDate['time']);
                    break;
                case 'comment':
                    $fields[] = array('code' => 'comment',
                        'label' => __('Delivery Comments'),
                        'value' => $deliveryDate['comment']);
                    break;
            }
        }
        return $fields;
    }

    public function getEditUrl() {
        return $this->getUrl('amasty_deliverydate/deliverydate/edit', ['order_id' => $this->_getOrderId()]);
    }

}