<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */


namespace Amasty\Deliverydate\Plugin\Grid;

use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult as GridSearchResult;
use Magento\Sales\Api\Data\OrderInterface;
use Amasty\Deliverydate\Model\Deliverydate;

/**
 * Add Data, Filters, Sorting functional for Order/Shipment/Invoice Adminhtml Grids for Delivery Date fields
 */
class SearchResult
{
    /**
     * key - grid column name
     * value - sql column name
     */
    const DELIVERY_COLUMN = [
            'amasty_deliverydate_date'    => 'amdeliverydate.date',
            'amasty_deliverydate_time'    => 'amdeliverydate.time',
            'amasty_deliverydate_comment' => 'amdeliverydate.comment'
        ];

    /**
     * @var \Amasty\Deliverydate\Model\ResourceModel\Deliverydate
     */
    private $deliverydateResource;

    /**
     * @var \Amasty\Deliverydate\Helper\Data
     */
    private $helper;

    /**
     * SearchResult constructor.
     *
     * @param \Amasty\Deliverydate\Model\ResourceModel\Deliverydate $deliverydateResource
     * @param \Amasty\Deliverydate\Helper\Data                      $helper
     */
    public function __construct(
        \Amasty\Deliverydate\Model\ResourceModel\Deliverydate $deliverydateResource,
        \Amasty\Deliverydate\Helper\Data $helper
    ) {
        $this->deliverydateResource = $deliverydateResource;
        $this->helper = $helper;
    }

    /**
     * @param GridSearchResult             $collection
     * @param \Magento\Framework\DB\Select $select
     *
     * @return \Magento\Framework\DB\Select
     */
    public function afterGetSelect(
        GridSearchResult $collection,
        $select
    ) {
        if ((string)$select && !array_key_exists('amdeliverydate', $select->getPart('from'))) {
            $select->joinLeft(
                ['amdeliverydate' => $this->deliverydateResource->getMainTable()],
                'main_table.entity_id = amdeliverydate.order_id',
                self::DELIVERY_COLUMN
            );
        }

        return $select;
    }

    /**
     * Prepare items delivery date to format for Grid
     *
     * @param GridSearchResult              $collection
     * @param \Magento\Framework\DataObject $item
     *
     * @return array
     */
    public function beforeAddItem(
        GridSearchResult $collection,
        \Magento\Framework\DataObject $item
    ) {
        $date = $item->getDataByKey('amasty_deliverydate_date');
        if ($date) {
            if ($date == Deliverydate::RESETED_DATE_VALUE) {
                $item->setData('amasty_deliverydate_date');
                return [$item];
            }
            $date = $this->helper->convertDateOutput($date);
            $item->setData('amasty_deliverydate_date', $date);
        }
        return [$item];
    }

    /**
     * @param GridSearchResult $collection
     * @param string           $field
     * @param string|null      $condition
     *
     * @return array
     */
    public function beforeAddFieldToFilter(
        GridSearchResult $collection,
        $field,
        $condition = null
    ) {
        if (array_key_exists($field, self::DELIVERY_COLUMN)) {
            $field = self::DELIVERY_COLUMN[$field];
        }
        if ($field == OrderInterface::INCREMENT_ID) {
            $field = 'main_table.' . OrderInterface::INCREMENT_ID;
        }

        return [$field, $condition];
    }
}
