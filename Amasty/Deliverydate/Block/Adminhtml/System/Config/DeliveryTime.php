<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */


namespace Amasty\Deliverydate\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class DeliveryTime extends Field
{
    /**
     * @param AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        $url = $this->getUrl('amasty_deliverydate/tinterval');
        $element->setComment(
            __(
                "In order to make Delivery Time option work, please specify Time Intervals in "
                . "<a target='_blank' href='%1'>Sales -> Time Intervals</a>" . " first.",
                $url
            )
        );

        return parent::render($element);
    }
}
