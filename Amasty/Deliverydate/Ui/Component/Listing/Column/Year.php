<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */


namespace Amasty\Deliverydate\Ui\Component\Listing\Column;

class Year implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var \Amasty\Deliverydate\Model\ResourceModel\Holidays\Collection
     */
    protected $collection;

    /**
     * Year options constructor.
     *
     * @param \Amasty\Deliverydate\Model\ResourceModel\Holidays\CollectionFactory $holidayCollectionFactory
     */
    public function __construct(
        \Amasty\Deliverydate\Model\ResourceModel\Holidays\CollectionFactory $holidayCollectionFactory
    ) {
        $this->collection = $holidayCollectionFactory->create();
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return $this->collection->getYearsAsArray('year');
    }
}
