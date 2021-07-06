<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */

namespace Amasty\Deliverydate\Model\Config\Source;

class Offset implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $timezone;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $datetime;

    public function __construct(
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \Magento\Framework\Stdlib\DateTime\DateTime $datetime
    )
    {
        $this->timezone = $timezone;
        $this->datetime = $datetime;
    }

    public function toOptionArray()
    {
        $options = array();

        for ($hour = -12; $hour <= 12; $hour++) {
            $offset = $hour > 0 ? "+$hour" : $hour;
            $hours = ($hour==1 || $hour==-1) ? '%1 hour %2': '%1 hours %2';
            $now = $this->timezone->scopeTimeStamp() + 3600 * $offset;
            $time = '(' . $this->datetime->date('H', $now) . ':' . $this->datetime->date('i', $now) . ')';
            $options[] = array(
                'value' => $offset,
                'label' => __($hours, $offset, $time),
            );
        }

        return $options;
    }
}
