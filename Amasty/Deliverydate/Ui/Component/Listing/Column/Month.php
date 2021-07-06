<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */


namespace Amasty\Deliverydate\Ui\Component\Listing\Column;

class Month implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var \Amasty\Deliverydate\Helper\Data
     */
    private $amhelper;

    /**
     * @var bool
     */
    private $eachMonthAvailable;

    /**
     * Month Options constructor.
     *
     * @param \Amasty\Deliverydate\Helper\Data $amhelper
     * @param bool                             $eachMonthAvailable
     */
    public function __construct(
        \Amasty\Deliverydate\Helper\Data $amhelper,
        $eachMonthAvailable = false
    ) {
        $this->amhelper = $amhelper;
        $this->eachMonthAvailable = $eachMonthAvailable;
    }

    public function toOptionArray()
    {
        $months = $this->amhelper->getMonths($this->eachMonthAvailable);
        $options = [];
        foreach ($months as $value => $month) {
            $options[] = ['value' => $value, 'label' => $month];
        }

        return $options;
    }
}
