<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */

namespace Amasty\Deliverydate\Model;

/**
 * @method \Amasty\Deliverydate\Model\ResourceModel\Holidays getResource()
 */
class Holidays extends \Magento\Framework\Model\AbstractModel
{
    /**#@+*/
    const SATURDAY = 6;
    const SUNDAY = 7;
    const HOLIDAY = 0;
    const WORKINGDAY = 1;
    /**#@-*/

    protected function _construct()
    {
        parent::_construct();
        $this->_init('Amasty\Deliverydate\Model\ResourceModel\Holidays');
        $this->setIdFieldName('holiday_id');
    }

    public function getStores()
    {
        return explode(',', $this->getStoreIds());
    }

    public function getStoreIds()
    {
        return $this->getData('store_ids');
    }

    /**
     * @return int
     */
    public function getYear()
    {
        return $this->getData('year');
    }

    /**
     * @return int
     */
    public function getMonth()
    {
        return $this->getData('month');
    }

    /**
     * @return int
     */
    public function getDay()
    {
        return $this->getData('day');
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->getData('day');
    }

    /**
     * @return int
     */
    public function getTypeDay()
    {
        return $this->getData('type_day');
    }

    /**
     * @param int $year
     *
     * @return $this
     */
    public function setYear($year)
    {
        $this->setData('year', $year);

        return $this;
    }

    /**
     * @param int $month
     *
     * @return $this
     */
    public function setMonth($month)
    {
        $this->setData('month', $month);

        return $this;
    }

    /**
     * @param int $day
     *
     * @return $this
     */
    public function setDay($day)
    {
        $this->setData('day', $day);

        return $this;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->setData('day', $description);

        return $this;
    }

    /**
     * @param int $type
     *
     * @return $this
     */
    public function setTypeDay($type)
    {
        $this->setData('type_day', $type);

        return $this;
    }
}
