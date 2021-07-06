<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */

namespace Amasty\Deliverydate\Block\Sales\Order\Info;

class Deliverydate extends \Amasty\Deliverydate\Block\Sales\Order\Email\Deliverydate
{
    protected function _construct()
    {
        parent::_construct();

        $this->setTemplate('Amasty_Deliverydate::info.phtml');
    }

    public function getFields()
    {
        return $this->deliveryHelper->whatShow('order_info');
    }

    /**
     * @return string
     */
    public function getEditUrl()
    {
        if ($this->customerSession->isLoggedIn()) {
            return $this->getUrl('amasty_deliverydate/deliverydate/edit', ['order_id' => $this->getOrderId()]);
        }
        return $this->getUrl('amasty_deliverydate/guest/edit', ['order_id' => $this->getOrderId()]);
    }

    /**
     * @param string $field code
     *
     * @return bool
     */
    public function isFieldEditable($field)
    {
        if ($field == 'date') {
            return $this->getDeliveryDate()->isCanEditOnFront();
        }

        return false;
    }
}
