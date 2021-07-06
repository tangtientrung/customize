<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */


namespace Amasty\Deliverydate\Model;

use Amasty\Deliverydate\Helper\Data as DeliverydateHelper;
use Amasty\Deliverydate\Model\ResourceModel\Deliverydate\CollectionFactory as DeliverydateCollectionFactory;
use Amasty\Deliverydate\Model\ResourceModel\Dinterval\CollectionFactory as DintervalCollectionFactory;
use Amasty\Deliverydate\Model\ResourceModel\Holidays\CollectionFactory as HolidaysCollectionFactory;
use Amasty\Deliverydate\Model\ResourceModel\Tinterval\CollectionFactory as TintervalCollectionFactory;
use Magento\Checkout\Model\ConfigProviderInterface;

/**
 * Class DeliverydateConfigProvider for provide settings
 */
class DeliverydateConfigProvider implements ConfigProviderInterface
{
    /**
     * In what format the calendar will send date
     * valuest from calendar to server should be in this format
     */
    const OUTPUT_DATE_FORMAT = 'MM/dd/yyyy';

    /**
     * @var array
     */
    private $quota = [];

    private $dayExceptions;

    /**
     * @var DeliverydateHelper
     */
    private $helper;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    private $date;

    /**
     * @var TintervalCollectionFactory
     */
    private $tintervalFactory;

    /**
     * @var \Magento\Framework\Data\Form\Filter\DateFactory
     */
    private $dateFactory;

    /**
     * @var DeliverydateCollectionFactory
     */
    private $deliverydateCollectionFactory;

    /**
     * @var DintervalCollectionFactory
     */
    private $dintervalCollectionFactory;

    /**
     * @var HolidaysCollectionFactory
     */
    private $holidaysCollectionFactory;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    private $checkoutSession;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    private $productCollectionFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * DeliverydateConfigProvider constructor.
     *
     * @param DeliverydateHelper $helper
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param TintervalCollectionFactory $tintervalFactory
     * @param \Magento\Framework\Data\Form\Filter\DateFactory $dateFactory
     * @param DeliverydateCollectionFactory $deliverydateCollectionFactory
     * @param DintervalCollectionFactory $dintervalCollectionFactory
     * @param HolidaysCollectionFactory $holidaysCollectionFactory
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     */
    public function __construct(
        DeliverydateHelper $helper,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        TintervalCollectionFactory $tintervalFactory,
        \Magento\Framework\Data\Form\Filter\DateFactory $dateFactory,
        DeliverydateCollectionFactory $deliverydateCollectionFactory,
        DintervalCollectionFactory $dintervalCollectionFactory,
        HolidaysCollectionFactory $holidaysCollectionFactory,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
    ) {
        $this->helper = $helper;
        $this->date = $date;
        $this->tintervalFactory = $tintervalFactory;
        $this->dateFactory = $dateFactory;
        $this->deliverydateCollectionFactory = $deliverydateCollectionFactory;
        $this->dintervalCollectionFactory = $dintervalCollectionFactory;
        $this->holidaysCollectionFactory = $holidaysCollectionFactory;
        $this->checkoutSession = $checkoutSession;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->storeManager = $storeManager;
    }

    /**
     * Retrieve assoc array of checkout configuration
     *
     * @return array
     */
    public function getConfig()
    {
        $days = $this->helper->getMinDays();
        $min = $this->date->timestamp() + 86400 * ((int)$days - 1); // 24 h. * 60 min. * 60 sec. = 86400 sec.
        $restrictTinterval = [];

        $collection = $this->deliverydateCollectionFactory->create()
            ->joinTinterval()
            ->getOlderThan($this->date->date('Y-m-d', $min));

        if (count($collection)) {
            /** @var \Amasty\Deliverydate\Model\Deliverydate $deliverydate */
            foreach ($collection as $deliverydate) {
                if (!array_key_exists($deliverydate->getDate(), $restrictTinterval)) {
                    $restrictTinterval[$deliverydate->getDate()] = [];
                }
                // collect time intervals with deliveries.
                $restrictTinterval[$deliverydate->getDate()][] = $deliverydate->getTintervalId();
            }
        }

        return [
            'amasty' => [
                'deliverydate' => [
                    'moduleEnabled' => (int)$this->helper->getStoreScopeValue('general/enabled'),
                    'generalComment' => $this->helper->getStoreScopeValue('general/comment'),
                    'generalCommentStyle' => $this->helper->getStoreScopeValue('general/comment_style'),
                    'dateEnabledCarriers' => (int)$this->helper->getStoreScopeValue('date_field/enabled_carriers'),
                    'dateShippingMethods' => explode(',', $this->helper->getStoreScopeValue('date_field/carriers')),
                    'timeEnabledCarriers' => (int)$this->helper->getStoreScopeValue('time_field/enabled_carriers'),
                    'timeShippingMethods' => explode(',', $this->helper->getStoreScopeValue('time_field/carriers')),
                    'commentEnabledCarriers' =>
                        (int)$this->helper->getStoreScopeValue('comment_field/enabled_carriers'),
                    'commentShippingMethods' =>
                        explode(',', $this->helper->getStoreScopeValue('comment_field/carriers')),
                    'restrictTinterval' => $restrictTinterval,
                    'defaultTime' => $this->getDefaultDeliveryTime()
                ]
            ]
        ];
    }

    /**
     * Collect config for Delivery Date field
     *
     * @return array
     */
    public function getDeliveryDateFieldConfig()
    {
        $config = [];

        $minDays = $this->helper->getMinDays();
        $maxDays = $this->helper->getStoreScopeValue('general/max_days');
        $disabledDays = $this->getDisabledDays();
        $this->helper->calculateWorkdays($disabledDays, $minDays, $maxDays);

        $config['days_week'] = $disabledDays;
        $config['min_days'] = $minDays;
        $config['max_days'] = $maxDays;
        $config['enabled_same_day'] = $this->helper->getStoreScopeValue('general/enabled_same_day');
        $config['time_same_day'] = $this->helper->getStoreScopeValue('general/same_day');
        $config['time_offset'] = $this->helper->getTimeOffset();
        $config['enabled_next_day'] = $this->helper->getStoreScopeValue('general/enabled_next_day');
        $config['time_next_day'] = $this->helper->getStoreScopeValue('general/next_day');
        $config['dintervals'] = $this->getDateIntervals();
        $config['quota'] = $this->getConfigQuota($config['min_days']);
        $config['inputDateFormat'] = strtoupper($this->getInputDateFormat());
        $config['outputDateFormat'] = strtoupper(DeliverydateConfigProvider::OUTPUT_DATE_FORMAT);
        $config['pickerDateTimeFormat'] = strtoupper($this->getPickerDateFormat());
        $config = $this->assignConfigDayException($config);

        return $config;
    }

    /**
     * @return int[]|null
     */
    public function getDisabledDays()
    {
        $daysOfWeek = $this->helper->getStoreScopeValue('general/disabled_days');
        if ($daysOfWeek !== null) {
            $daysOfWeek = array_map('intval', explode(',', $daysOfWeek));
        }

        return $daysOfWeek;
    }

    /**
     * Get default Delivery Date in selected format.
     *
     * @return null|string
     */
    public function getDefaultDeliveryDate()
    {
        if ($this->helper->getStoreScopeValue('date_field/enabled_default')) {
            $minDays = $this->helper->getMinDays();
            $dayOffset = $this->helper->getStoreScopeValue('date_field/default');
            $dayOffset = max($minDays, $dayOffset);

            $timestamp = $this->date->timestamp() + ($dayOffset * 24 * 60 * 60);
            $date = $this->date->date('Y-m-d', $timestamp);
            $filter = $this->dateFactory->create(['format' => $this->getInputDateFormat()]);

            return $filter->outputFilter($date);
        }

        return null;
    }

    /**
     * Get default Delivery Time in selected format.
     *
     * @return null|int
     */
    public function getDefaultDeliveryTime()
    {
        if ($this->helper->getStoreScopeValue('time_field/enabled_default')) {
            return $this->helper->getStoreScopeValue('time_field/default');
        }

        return null;
    }

    /**
     * In what format the calendar will show on front.
     * in calendar.js always long year format on frontend.
     *
     * @return string
     */
    public function getPickerDateFormat()
    {
        $format = $this->helper->getStoreScopeValue('date_field/format');

        // covert short year to long format. For calendar.js
        return preg_replace('/y{2,}/s', 'yyyy', $format);
    }

    /**
     * Calendar input format
     * Values from server to calendar should be in this format
     *
     * @return string
     */
    public function getInputDateFormat()
    {
        return self::OUTPUT_DATE_FORMAT;
    }

    /**
     * Get exception days from collection
     *
     * @param array $config
     *
     * @return array
     */
    protected function assignConfigDayException($config)
    {
        $days = $this->getDayException();
        $config['holidays'] = $days['holidays'];
        $config['workingdays'] = $days['workingdays'];

        return $config;
    }

    public function getDayException()
    {
        if ($this->dayExceptions !== null) {
            return $this->dayExceptions;
        }
        $this->dayExceptions['holidays'] = $this->dayExceptions['workingdays'] = [];
        $storeId = $this->storeManager->getStore()->getId();
        /** @var \Amasty\Deliverydate\Model\ResourceModel\Holidays\Collection $daysWithFilters */
        $daysWithFilters = $this->holidaysCollectionFactory->create();

        /** @var \Amasty\Deliverydate\Model\Holidays $date */
        foreach ($daysWithFilters as $date) {
            $stores = explode(',', $date->getStoreIds());
            if (!in_array($storeId, $stores) && !in_array(0, $stores)) {
                continue;
            }
            switch ($date->getTypeDay()) {
                case Holidays::HOLIDAY:
                    $this->dayExceptions['holidays'][$date->getYear()][$date->getMonth()][$date->getDay()] = true;
                    break;
                case Holidays::WORKINGDAY:
                    $this->dayExceptions['workingdays'][$date->getYear()][$date->getMonth()][$date->getDay()] = true;
                    break;
            }
        }

        return $this->dayExceptions;
    }

    /**
     * Check shipping limit and assign to config restricted dates
     *
     * @param int $minDays
     *
     * @return array $restrictedDates [year][moth][day] contains restricted days by quota
     */
    public function getConfigQuota($minDays)
    {
        if (array_key_exists('d' . $minDays, $this->quota)) {
            return $this->quota['d' . $minDays];
        }
        $restrictedDates = [];
        if ($this->helper->getDefaultScopeValue('quota/quota_type')) {
            // 24 h. * 60 min. * 60 sec. = 86400 sec.
            $min = $this->date->timestamp() + 86400 * ((int)$minDays - 1);
            $collection = $this->deliverydateCollectionFactory->create();
            $collection->getOlderThan($this->date->date('Y-m-d', $min));
            if ($collection->getSize() > 0) {
                $dates = [];
                foreach ($collection as $delivery) {
                    $dates[] = $delivery->getDate();
                }
                $deliveries = array_count_values($dates);
                foreach ($deliveries as $date => $count) {
                    $quota = $this->getDayQuota($date);
                    if ($quota && $count >= $quota) {
                        $restrictedDates
                        [$this->date->date('Y', $date)]
                        [$this->date->date('n', $date)]
                        [$this->date->date('d', $date)]
                            = true;
                    }
                }
            }
        }

        return $this->quota['d' . $minDays] = $restrictedDates;
    }

    /**
     * @param int|string $date date in GMT timezone
     *
     * @return int
     */
    public function getDayQuota($date)
    {
        $quota = 0;
        switch ($this->helper->getWebsiteScopeValue('quota/quota_type')) {
            case 'day':
                $quota = $this->helper->getWebsiteScopeValue('quota/per_day');
                break;
            case 'week_day':
                $weekDay = $this->date->date('N', $date);
                if ($weekDay >= 1 && $weekDay <= 7) {
                    $quota = $this->helper->getWebsiteScopeValue('quota/per' . $weekDay);
                }
                break;
        }

        return (int)$quota;
    }

    /**
     * return assoc array of date intervals
     *
     * @return array
     */
    public function getDateIntervals()
    {
        $dintervals = $this->dintervalCollectionFactory->create()
            ->filterByStore($this->storeManager->getStore()->getId());

        $dintervalsResult = [];
        foreach ($dintervals as $dinterval) {
            $dintervalsResult[] = [
                'from' => [
                    'year' => $dinterval->getFromYear(),
                    'month' => $dinterval->getFromMonth(),
                    'day' => $dinterval->getFromDay()
                ],
                'to' => [
                    'year' => $dinterval->getToYear(),
                    'month' => $dinterval->getToMonth(),
                    'day' => $dinterval->getToDay()
                ]
            ];
        }

        return $dintervalsResult;
    }

    /**
     * @param int|null $minDay
     * @return int
     */
    public function shiftMinDay($minDay = null)
    {
        $minDay = $this->excludeVacation($minDay);

        return $minDay;
    }

    /**
     * @param int|null $minDay
     * @return int
     */
    private function excludeVacation($minDay = null)
    {
        if (empty($minDay)) {
            $minDay = 0;
        }

        $minDayTimestamp = $this->date->timestamp('+' . $minDay . ' days');

        if ($weekDay = $this->dayIsWeekend($minDayTimestamp)) {
            $minDay += (($weekDay == Holidays::SATURDAY) ? 2 : 1);
            $minDay = $this->excludeVacation($minDay);
        } else {
            if ($this->restrictHolidays($minDayTimestamp) || $this->restrictDateInterval($minDayTimestamp)) {
                $minDay++;
                $minDay = $this->excludeVacation($minDay);
            }
        }

        return $minDay;
    }

    /**
     * @param string|null $timestamp
     * @return bool|string
     */
    private function dayIsWeekend($timestamp = null)
    {
        $weekDay = $this->date->date('w', $timestamp);

        return ($weekDay == Holidays::SATURDAY || $weekDay == Holidays::SUNDAY) ? $weekDay : false;
    }

    /**
     * @param string|null $timestamp
     * @return bool
     */
    private function restrictDateInterval($timestamp = null)
    {
        $isNeedRestrict = false;

        foreach ($this->getDateIntervals() as $interval) {
            $from = $interval['from'];
            $to = $interval['to'];

            $fromTimestamp = $this->date->timestamp($from['year'] . '-' . $from['month'] . '-' . $from['day']);
            $toTimestamp = $this->date->timestamp($timestamp['year'] . '-' . $to['month'] . '-' . $to['day']);

            if ($timestamp >= $fromTimestamp && $timestamp <= $toTimestamp) {
                $isNeedRestrict = true;
                break;
            }
        }

        return $isNeedRestrict;
    }

    /**
     * @param string|null $timestamp
     * @return bool
     */
    public function restrictHolidays($timestamp = null)
    {
        $isNeedRestrict = false;
        $exceptionDays = $this->getDayException();
        $holidays = (isset($exceptionDays['holidays'])) ? $exceptionDays['holidays'] : [];

        $actualDays = [];
        foreach ($holidays as $year => $yearEntry) {
            foreach ($yearEntry as $month => $monthEntry) {
                foreach ($monthEntry as $day => $dayEntry) {
                    $actualDays[] = ['year' => $year, 'month' => $month, 'day' => $day];
                }
            }
        }

        foreach ($actualDays as $actualDay) {
            $holidayTimestamp = $this->date->timestamp(
                $actualDay['year'] . '-' . $actualDay['month'] . '-' . $actualDay['day']
            );

            if ($holidayTimestamp == $timestamp) {
                $isNeedRestrict = true;
                break;
            }
        }

        return $isNeedRestrict;
    }
}
