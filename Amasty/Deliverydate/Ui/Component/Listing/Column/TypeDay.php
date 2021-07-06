<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */


namespace Amasty\Deliverydate\Ui\Component\Listing\Column;

class TypeDay implements \Magento\Framework\Data\OptionSourceInterface
{

    /**
     * @var \Amasty\Deliverydate\Helper\Data
     */
    protected $amhelper;


    public function __construct(
        \Amasty\Deliverydate\Helper\Data $amhelper
    )
    {
        $this->amhelper = $amhelper;

    }

    public function toOptionArray()
    {
        $typeDays = $this->amhelper->getTypeDay();
        $options = [];
        foreach ($typeDays as $value => $typeDay) {
            $options[] = ['value' => $value, 'label' => $typeDay];
        }

        return $options;
    }
}
