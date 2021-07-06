<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */

namespace Amasty\Deliverydate\Model\Config\Source;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Carriers implements \Magento\Framework\Option\ArrayInterface
{

    protected $_shippingConfig;
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    public function __construct(
        \Magento\Shipping\Model\Config $shippingConfig,
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->_shippingConfig = $shippingConfig;
        $this->scopeConfig = $scopeConfig;
    }

    public function toOptionArray()
    {
        $methods = array();
        $activeCarriers = $this->_shippingConfig->getActiveCarriers();
        foreach ($activeCarriers as $carrierCode => $carrierModel) {
            $options = array();
            if ($carrierMethods = $carrierModel->getAllowedMethods()) {
                foreach ($carrierMethods as $methodCode => $method) {
                    $code = $carrierCode . '_' . $methodCode;
                    $options[] = array('value' => $code, 'label' => $method);
                }
                $carrierTitle = $this->scopeConfig->getValue('carriers/'.$carrierCode.'/title');
                $methods[] = array('value' => $options, 'label' => $carrierTitle);
            }
        }
        return $methods;
    }

}
