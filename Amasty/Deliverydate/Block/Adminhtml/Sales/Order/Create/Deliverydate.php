<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */


namespace Amasty\Deliverydate\Block\Adminhtml\Sales\Order\Create;

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
     * @var \Magento\Framework\Data\FormFactory
     */
    protected $formFactory;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Amasty\Deliverydate\Model\ResourceModel\Deliverydate
     */
    protected $deliverydateResourceModel;

    /**
     * @var \Magento\Backend\Model\Session\Quote
     */
    protected $sessionQuote;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;

    /**
     * @var \Amasty\Deliverydate\Model\DeliverydateConfigProvider
     */
    private $configProvider;

    public function __construct(
        Context $context,
        DeliverydateFactory $deliveryDateFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Amasty\Deliverydate\Helper\Data $deliveryHelper,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Amasty\Deliverydate\Model\ResourceModel\Deliverydate $deliverydateResourceModel,
        \Magento\Backend\Model\Session\Quote $sessionQuote,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Amasty\Deliverydate\Model\DeliverydateConfigProvider $configProvider,
        array $data = []
    ) {
        $this->deliveryDateFactory = $deliveryDateFactory;
        $this->coreRegistry = $coreRegistry;
        $this->deliveryHelper = $deliveryHelper;
        $this->sessionQuote = $sessionQuote;
        $this->deliverydateResourceModel = $deliverydateResourceModel;
        $this->formFactory = $formFactory;
        $this->scopeConfig = $context->getScopeConfig();
        $this->date = $date;
        $this->configProvider = $configProvider;
        parent::__construct($context, $data);
    }

    protected function _construct()
    {
        parent::_construct();

        $deliveryDate = $this->deliveryDateFactory->create();
        $orderId = 0;
        if ($this->sessionQuote->getOrderId()) { // edit order
            $orderId = $this->sessionQuote->getOrderId();
        } elseif ($this->sessionQuote->getReordered()) { // reorder
            $orderId = $this->sessionQuote->getReordered();
        }

        if ($orderId) {
            $this->deliverydateResourceModel->load($deliveryDate, $orderId, 'order_id');
            $this->coreRegistry->register('current_deliverydate', $deliveryDate);
        }

        $this->setTemplate('Amasty_Deliverydate::delivery_create.phtml');
    }

    public function getFormElements()
    {
        $form = $this->formFactory->create();
        $form->setHtmlIdPrefix('amasty_deliverydate_');
        $availableFields = $this->deliveryHelper->whatShow('order_create');
        $storeId = $this->_storeManager->getStore()->getStoreId();
        if ($this->coreRegistry->registry('rule_data')) {
            $storeId = $this->coreRegistry->registry('rule_data')->getStoreId();
        }

        $fieldset = $form->addFieldset(
            'base_fieldset',
            [
                'legend' => __('Delivery Date'),
                'class' => 'amasty-deliverydate-fieldset'
            ]
        );

        if (in_array('date', $availableFields)) {
            $date = $fieldset->addField(
                'date',
                \Amasty\Deliverydate\Block\Adminhtml\Sales\Order\Renderer\Date::class,
                [
                    'label' => __('Delivery Date'),
                    'name' => 'amdeliverydate[date]',
                    'input_format' => \Magento\Framework\Stdlib\DateTime::DATE_INTERNAL_FORMAT,
                    'style' => 'width: 40%',
                    'format' => $this->deliveryHelper->getDefaultScopeValue('date_field/format'),
                    'required' => false,
                    'date_format' => $this->deliveryHelper->getDefaultScopeValue('date_field/format'),
                    'min_date' => $this->date->date($this->deliveryHelper->getPhpFormat()),
                    'value' => $this->configProvider->getDefaultDeliveryDate()
                ]
            );
        }

        if ($this->scopeConfig->getValue('amdeliverydate/time_field/enabled_time')
            && in_array('time', $availableFields)) {
            $options = $this->deliveryHelper->getTIntervals($storeId);
            $fieldset->addField(
                'tinterval_id',
                'select',
                [
                    'label' => __('Delivery Time Interval'),
                    'name' => 'amdeliverydate[tinterval_id]',
                    'style' => 'width: 40%',
                    'required' => false,
                    'value' => $this->configProvider->getDefaultDeliveryTime(),
                    'options' => $options,
                ]
            );
        }

        if ($this->scopeConfig->getValue('amdeliverydate/comment_field/enabled_comment')
            && in_array('comment', $availableFields)) {
            $fieldset->addField(
                'comment',
                'textarea',
                [
                    'label' => __('Delivery Comments'),
                    'title' => __('Delivery Comments'),
                    'name' => 'amdeliverydate[comment]',
                    'required' => false,
                    'style' => 'width: 40%',
                ]
            );
        }

        if ($deliveryDate = $this->coreRegistry->registry('current_deliverydate')) {
            $data = $deliveryDate->getData();
            if (isset($data['date']) && '0000-00-00' == $data['date']) {
                $data['date'] = '';
            }
            $form->setValues($data);
        }

        return $form->getElements();
    }
}
