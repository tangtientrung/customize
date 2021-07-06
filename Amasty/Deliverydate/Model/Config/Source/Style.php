<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */

namespace Amasty\Deliverydate\Model\Config\Source;

class Style implements \Magento\Framework\Option\ArrayInterface
{

    public function toOptionArray()
    {
        return array(
            array(
                'value' => 'as_is',
                'label' => __('As is')
            ),
            array(
                'value' => 'notice',
                'label' => __('Magento Notice')
            ),
        );
    }
}
