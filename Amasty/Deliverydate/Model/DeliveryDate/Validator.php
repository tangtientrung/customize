<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */


namespace Amasty\Deliverydate\Model\DeliveryDate;

use Amasty\Deliverydate\Helper\Data as DeliverydateHelper;
use Amasty\Deliverydate\Model\DeliverydateConfigProvider;
use Amasty\Deliverydate\Model\Holidays;
use Magento\Framework\Stdlib\DateTime as DateTimeConverter;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

/**
 * Class Validator for validation delivery
 */
class Validator
{
    /**
     * @var null|int
     */
    private $minDayConfig = null;

    /**
     * @var DateDataObject
     */
    private $currentDeliveryDate;

    /**
     * @var DeliverydateHelper
     */
    private $helper;

    /**
     * @var DateTime
     */
    private $dateLib;

    /**
     * @var \Magento\Framework\Data\Form\Filter\DateFactory
     */
    private $dateFactory;

    /**
     * @var DeliverydateConfigProvider
     */
    private $configProvider;
    /**
     * @var DeliverydateHelper
     */
    private $deliveryHelper;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $localeDate;

    public function __construct(
        DeliverydateHelper $helper,
        DateTime $dateLib,
        \Magento\Framework\Data\Form\Filter\DateFactory $dateFactory,
        DeliverydateConfigProvider $configProvider,
        DateDataObjectFactory $dataObjectFactory,
        \Amasty\Deliverydate\Helper\Data $deliveryHelper,
        TimezoneInterface $localeData
    ) {
        $this->helper = $helper;
        $this->dateLib = $dateLib;
        $this->dateFactory = $dateFactory;
        $this->configProvider = $configProvider;
        $this->currentDeliveryDate = $dataObjectFactory->create();
        $this->deliveryHelper = $deliveryHelper;
        $this->localeDate = $localeData;

        $this->todayTimestamp = $this->dateLib->timestamp(date('j F Y'));
        $this->currentTimestamp = $this->localeDate->scopeTimeStamp()
            + 3600 * $this->deliveryHelper->getDefaultScopeValue('general/offset');
    }

    /**
     * Validate Delivery Date
     *
     * @param string $deliveryDate date in mysql format YYYY-mm-dd
     * @param $timeWithInterval
     * @return bool
     */
    public function validate($deliveryDate, $timeWithInterval)
    {
        if (!$deliveryDate) {
            return false;
        }
        $this->setCurrentDeliveryDate($deliveryDate, $timeWithInterval);
        switch (true) {
            case $this->disableSameDay():
            case $this->disableNextDay():
            case $this->restrictByQuota():
            case $this->restrictDateLessToday():
                return false;
            case $this->notRestrictWorkingDays():
                return true;
            case $this->minDays():
            case $this->maxDays():
            case $this->restrictDateInterval():
            case $this->restrictHolidays():
            case $this->daysOfWeek():
                return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function disablePastTime()
    {
        if ($this->helper->getStoreScopeValue('time_field/enabled_time')) {
            $selectedTime = $this->currentDeliveryDate->getTimeWithInterval();
            $isValid = $selectedTime <= $this->currentTimestamp;
        } else {
            $isValid = $this->currentTimestamp <= $this->todayTimestamp;
        }

        return $isValid;
    }

    /**
     * @param string $deliveryDate
     * @param $timeWithInterval
     */
    private function setCurrentDeliveryDate($deliveryDate, $timeWithInterval)
    {
        $this->currentDeliveryDate->setDate($deliveryDate);
        $timestamp = $this->dateLib->timestamp($deliveryDate);
        $this->currentDeliveryDate->setObject(new \Zend_Date($timestamp, \Zend_Date::TIMESTAMP));
        $this->currentDeliveryDate->setTimestamp($timestamp);
        $this->currentDeliveryDate->setYear($this->dateLib->date('Y', $timestamp));
        $this->currentDeliveryDate->setMonth($this->dateLib->date('n', $timestamp));
        $this->currentDeliveryDate->setDay($this->dateLib->date('d', $timestamp));
        $this->currentDeliveryDate->setTimeWithInterval($timeWithInterval);
    }

    /**
     * @return bool
     */
    private function disableSameDay()
    {
        if ($this->helper->getStoreScopeValue('general/enabled_same_day')
            && $this->currentDeliveryDate->getObject()->isToday()
        ) {
            $disableAfter = $this->setDateTime($this->helper->getStoreScopeValue('general/same_day'));
            $now = $this->dateLib->timestamp();
            $offset = $this->helper->getTimeOffset();
            if ($offset > 0) {
                $now = $this->dateLib->timestamp('+' . $offset . 'hour');
            } elseif ($offset < 0 || strpos($offset, '-') !== false) {
                $now = $this->dateLib->timestamp('-' . $offset . 'hour');
            }

            if ($now > $disableAfter) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    private function disableNextDay()
    {
        if ($this->helper->getStoreScopeValue('general/enabled_next_day')
            && ($this->currentDeliveryDate->getObject()->isTomorrow() || $this->isWorkingDayAfterHoliday())
        ) {
            $disableAfter = $this->setDateTime($this->helper->getStoreScopeValue('general/next_day'));
            $now = $this->dateLib->timestamp();
            $offset = $this->helper->getTimeOffset();
            if ($offset > 0) {
                $now = $this->dateLib->timestamp('+' . $offset . 'hour');
            } elseif ($offset < 0 || strpos($offset, '-') !== false) {
                $now = $this->dateLib->timestamp('-' . $offset . 'hour');
            }

            if ($now > $disableAfter) {
                return true;
            }
        }
        return false;
    }

    /**
     * make timestamp for compare time
     *
     * @param string $timeString
     *
     * @return false|int
     */
    private function setDateTime($timeString)
    {
        list($hour, $minute, $second) = explode(',', $timeString);

        return mktime($hour, $minute, $second);
    }

    /**
     * Is need to restrict day by Quota
     * Is limit for shipping quota of day is not exceeded
     *
     * @returns boolean
     */
    private function restrictByQuota()
    {
        $quota = $this->configProvider->getConfigQuota($this->getMinDayConfig());
        $date = $this->currentDeliveryDate;

        return $this->validateQuoteByDate($quota, $date);
    }

    /**
     * @param array $quota
     * @param \Amasty\Deliverydate\Model\DeliveryDate\DateDataObject $date
     *
     * @return bool
     */
    private function validateQuoteByDate($quota, $date)
    {
        $year = $date->getYear();
        $month = $date->getMonth();
        $day = $date->getDay();

        return isset($quota[$year][$month][$day]) && $quota[$year][$month][$day];
    }

    /**
     * @return bool
     */
    private function restrictDateLessToday()
    {
        return $this->dateLib->date('Ymd', $this->currentDeliveryDate->getTimestamp()) < $this->dateLib->date('Ymd');
    }

    /**
     * @return bool
     */
    private function notRestrictWorkingDays()
    {
        $days = $this->configProvider->getDayException();

        return $this->restrictDate($days['workingdays']);
    }

    /**
     * Is need to restrict day by Date Interval
     *
     * @returns boolean
     */
    private function restrictDateInterval()
    {
        $isNeedRestrict = false;
        foreach ($this->configProvider->getDateIntervals() as $interval) {
            $from = $interval['from'];
            $to = $interval['to'];

            if ($from['year'] == 0 || $to['year'] == 0) {
                // is interval for each year
                $from['year'] = $to['year'] = $this->currentDeliveryDate->getYear();
            }
            if ($from['month'] == 0 || $to['month'] == 0) {
                // is interval for each month
                $from['month'] = $to['month'] = $this->currentDeliveryDate->getMonth();
            }
            $inputDate = $this->currentDeliveryDate->getTimestamp();
            $fromDate  = $this->dateLib->timestamp($from['year'] . '-' . $from['month'] . '-' . $from['day']);
            $toDate  = $this->dateLib->timestamp($to['year'] . '-' . $to['month'] . '-' . $to['day']);
            if ($fromDate > $toDate) {
                // revert interval
                // restrict all days in same year from fromDate and to toDate
                if (($from['year'] <= $this->currentDeliveryDate->getYear()
                        && $to['year'] >= $this->currentDeliveryDate->getYear())
                    && ($inputDate >= $fromDate || $inputDate <= $toDate)
                ) {
                    $isNeedRestrict = true;
                    break;
                }
            } else {
                if ($inputDate >= $fromDate && $inputDate <= $toDate) {
                    $isNeedRestrict = true;
                    break;
                }
            }
        }

        return $isNeedRestrict;
    }

    /**
     * Is need to restrict day as Holidays
     *
     * @returns boolean
     */
    private function restrictHolidays()
    {
        $days = $this->configProvider->getDayException();

        return $this->restrictDate($days['holidays']);
    }

    /**
     * Is need to restrict day
     *
     * @param array $restrict
     * @returns boolean
     */
    private function restrictDate($restrict)
    {
        $date = $this->currentDeliveryDate;
        $year = $date->getYear();
        $month = $date->getMonth();
        $day = $date->getDay();
        if (isset($restrict[$year][$month][$day])) {
            return $restrict[$year][$month][$day];
        }
        // 0 - for all month
        if (isset($restrict[$year][0][$day])) {
            return $restrict[$year][0][$day];
        }
        // 0 - for all year
        if (isset($restrict[0][$month][$day])) {
            return $restrict[0][$month][$day];
        }
        if (isset($restrict[0][0][$day])) {
            return $restrict[0][0][$day];
        }

        return false;
    }

    /**
     * Is current day or check day of the week restricted
     *
     * @param string|null $checkDate
     *
     * @return bool
     */
    private function daysOfWeek($checkDate = null)
    {
        $days = $this->configProvider->getDisabledDays();

        if (!$checkDate) {
            $checkDate = $this->currentDeliveryDate->getTimestamp();
        }

        return $days && in_array($this->dateLib->date('N', $checkDate), $days);
    }

    /**
     * Validate Minimal Delivery Interval
     *
     * @return bool
     */
    private function minDays()
    {
        $minDay = $this->helper->getMinDays();

        if ($this->helper->getStoreScopeValue('general/min_days_exclude')) {
            $minDay = $this->configProvider->shiftMinDay($minDay);
        }

        // 24 h. * 60 min. * 60 sec. = 86400 sec
        $minDayTimestamp = $this->todayTimestamp + $minDay * 86400;

        return $this->currentDeliveryDate->getTimestamp() < $minDayTimestamp;
    }

    /**
     * @return int|null
     */
    private function getMinDayConfig()
    {
        if ($this->minDayConfig === null) {
            $this->minDayConfig = $this->helper->getMinDays();
        }

        return $this->minDayConfig;
    }

    /**
     * Validate Maximal Delivery Interval
     *
     * @return bool
     */
    private function maxDays()
    {
        $config = (int)$this->helper->getStoreScopeValue('general/max_days');
        if ($config <= 0) {
            return false;
        }
        // 24 h. * 60 min. * 60 sec. = 86400 sec
        $maxDay = $this->todayTimestamp + $config * 86400;

        return $this->currentDeliveryDate->getTimestamp() > $maxDay;
    }

    /**
     * @return bool
     */
    private function isWorkingDayAfterHoliday()
    {
        $previousDate = strtotime($this->currentDeliveryDate->getDate() . '-1 day');

        return $this->daysOfWeek($previousDate) || $this->configProvider->restrictHolidays($previousDate);
    }
}
