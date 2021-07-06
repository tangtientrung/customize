<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */


namespace Amasty\Deliverydate\Block\Checkout;

use Amasty\Deliverydate\Helper\Data;
use Amasty\Deliverydate\Model\DeliverydateConfigProvider;
use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;

class LayoutProcessor implements LayoutProcessorInterface
{
    /**
     * @var Data
     */
    protected $deliveryHelper;

    /**
     * @var \Amasty\Deliverydate\Model\ResourceModel\Tinterval\Collection
     */
    protected $tintervalCollection;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Amasty\Deliverydate\Model\DeliveryDate
     */
    protected $deliveryDate;

    /**
     * @var DeliverydateConfigProvider
     */
    private $configProvider;

    /**
     * @var \Amasty\Deliverydate\Model\Tinterval
     */
    protected $tintervalModel;

    public function __construct(
        Data $deliveryHelper,
        \Amasty\Deliverydate\Model\ResourceModel\Tinterval\Collection $tintervalCollection,
        \Magento\Customer\Model\Session $customerSession,
        DeliverydateConfigProvider $configProvider,
        \Amasty\Deliverydate\Model\Deliverydate $deliveryDate,
        \Amasty\Deliverydate\Model\Tinterval $tintervalModel
    ) {
        $this->deliveryHelper = $deliveryHelper;
        $this->tintervalCollection = $tintervalCollection;
        $this->customerSession = $customerSession;
        $this->configProvider = $configProvider;
        $this->deliveryDate = $deliveryDate;
        $this->tintervalModel = $tintervalModel;
    }

    /**
     * @param array $jsLayout
     *
     * @return array
     */
    public function process($jsLayout)
    {
        if ($this->deliveryHelper->moduleEnabled()) {
            $elements = [];

            if ($this->showByGroup('date_field')) {
                $elements['deliverydate_date'] = [
                    'component' => 'Amasty_Deliverydate/js/checkout/date',
                    'label' => __('Delivery Date'),
                    'sortOrder' => 200,
                    'disabled' => false,
                    'validation' => [
                        'validate-date' => true
                    ],
                    'additionalClasses' => 'date',
                    'dataScope' => 'shippingAddress.amdeliverydate_date',
                    'provider' => 'checkoutProvider',
                    'notice' => $this->deliveryHelper->getStoreScopeValue('date_field/note'),
                    'visible' => !$this->deliveryHelper->getStoreScopeValue('date_field/enabled_carriers'),
                    'config' => [
                        'template' => 'ui/form/field',
                        'id' => 'delivery-date',
                        'options' => [
                            'showOn' => 'both',
                            'amdeliveryconf' => $this->configProvider->getDeliveryDateFieldConfig()
                        ],
                        'readonly' => 1,
                        'pickerDefaultDateFormat' => $this->configProvider->getPickerDateFormat(),
                        'pickerDateTimeFormat' => $this->configProvider->getPickerDateFormat(),
                        'outputDateFormat' => DeliverydateConfigProvider::OUTPUT_DATE_FORMAT,
                        'inputDateFormat' => $this->configProvider->getInputDateFormat(),
                        'default' => $this->configProvider->getDefaultDeliveryDate(),
                        'currentDate' => $this->deliveryDate->getCurrentStoreDate()
                    ]
                ];
                if ((int)$this->deliveryHelper->getStoreScopeValue('date_field/required') == 1) {
                    $elements['deliverydate_date']['validation']['required-entry'] = true;
                }
            }

            if ($this->deliveryHelper->getStoreScopeValue('time_field/enabled_time')
                && $this->showByGroup('time_field')
            ) {
                $optionsForOtherDays = $this->tintervalCollection->toOptionArray();
                $optionsForCurrentDay = $this->tintervalCollection->clear()->getValidTinterval()->toOptionArray();
                $optionsForCurrentDay = $this->tintervalModel->restrictCurrentTinterval($optionsForCurrentDay);

                if (!empty($optionsForOtherDays)) {
                    $elements['deliverydate_time'] = [
                        'component' => 'Amasty_Deliverydate/js/checkout/select',
                        'label' => __('Delivery Time Interval'),
                        'sortOrder' => 201,
                        'disabled' => false,
                        'validation' => [
                            'required-entry' => (bool)$this->deliveryHelper
                                ->getStoreScopeValue('time_field/required')
                        ],
                        'dataScope' => 'shippingAddress.amdeliverydate_time',
                        'notice' => $this->deliveryHelper->getStoreScopeValue('time_field/note'),
                        'visible' => !$this->deliveryHelper->getStoreScopeValue('time_field/enabled_carriers'),
                        'provider' => 'checkoutProvider',
                        'config' => [
                            'template' => 'ui/form/field',
                            'caption' => __('Please select time interval.'),
                            'options' => [
                                'full' => $optionsForOtherDays,
                                'abridge' => $optionsForCurrentDay
                            ]
                        ]
                    ];
                }
            }

            if ($this->deliveryHelper->getStoreScopeValue('comment_field/enabled_comment')
                && $this->showByGroup('comment_field')
            ) {
                $validation = [
                    'required-entry' => (bool)$this->deliveryHelper->getStoreScopeValue('comment_field/required')
                ];
                if ($maxLength = (int)$this->deliveryHelper->getStoreScopeValue('comment_field/maxlength')) {
                    $validation['max_text_length'] = $maxLength;
                }

                $elements['deliverydate_comment'] = [
                    'component' => 'Magento_Ui/js/form/element/textarea',
                    'label' => __('Delivery Comments'),
                    'sortOrder' => 202,
                    'validation' => $validation,
                    'dataScope' => 'shippingAddress.amdeliverydate_comment',
                    'provider' => 'checkoutProvider',
                    'notice' => $this->deliveryHelper->getStoreScopeValue('comment_field/note'),
                    'visible' => !$this->deliveryHelper->getStoreScopeValue('comment_field/enabled_carriers'),
                    'config' => [
                        'template' => 'ui/form/field',
                        'cols' => 5,
                        'rows' => 5,
                        'elementTmpl' => 'ui/form/element/textarea',
                        'options' => []
                    ]
                ];
            }

            if (!empty($elements)) {
                if (isset(
                    $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
                    ['shippingAddress']['children']['shippingAdditional']['children']['amasty-delivery-date']['children']
                )
                ) {
                    foreach ($elements as $key => $element) {
                        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
                        ['shippingAddress']['children']['shippingAdditional']['children']['amasty-delivery-date']['children'][$key] =
                            $element;
                    }
                }
            }
        }

        return $jsLayout;
    }

    /**
     * Is current Customer Group can view field
     *
     * @param string $field
     *
     * @return bool
     */
    protected function showByGroup($field)
    {
        if ($this->deliveryHelper->getStoreScopeValue($field . '/enabled_customer_groups')) {
            $groupId = $this->customerSession->getCustomerGroupId();
            $groups = explode(',', $this->deliveryHelper->getStoreScopeValue($field . '/customer_groups'));
            if (!in_array($groupId, $groups)) {
                return false;
            }
        }

        return true;
    }
}
