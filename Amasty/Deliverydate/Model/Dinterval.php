<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */

namespace Amasty\Deliverydate\Model;

class Dinterval extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        parent::_construct();
        $this->_init('Amasty\Deliverydate\Model\ResourceModel\Dinterval');
        $this->setIdFieldName('dinterval_id');
    }

    /**
     * Is Date Interval for Each Year
     *
     * @return bool
     */
    public function isForEachYear()
    {
        $fromYear  = $this->getFromYear();
        $toYear    = $this->getToYear();
        return ($fromYear == 0 || $toYear == 0) && $fromYear !== null && $toYear !== null;
    }

    /**
     * Is Date Interval for Each Month
     *
     * @return bool
     */
    public function isForEachMonth()
    {
        $fromMonth  = $this->getFromMonth();
        $toMonth    = $this->getToMonth();
        return ($fromMonth == 0 || $toMonth == 0) && $fromMonth !== null && $toMonth !== null;
    }

    /**
     * @return $this
     */
    public function setForEachYear()
    {
        $this->setData('from_year', 0);
        $this->setData('to_year', 0);
        return $this;
    }

    /**
     * @return $this
     */
    public function setForEachMonth()
    {
        $this->setData('from_month', 0);
        $this->setData('to_month', 0);
        return $this;
    }

    /**
     * @return string|int
     */
    public function getToYear()
    {
        return $this->_getData('to_year');
    }

    /**
     * @return string|int
     */
    public function getFromYear()
    {
        return $this->_getData('from_year');
    }

    /**
     * @return string|int
     */
    public function getToMonth()
    {
        return $this->_getData('to_month');
    }

    /**
     * @return string|int
     */
    public function getFromMonth()
    {
        return $this->_getData('from_month');
    }
}
