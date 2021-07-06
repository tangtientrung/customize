<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */


namespace Amasty\Deliverydate\Ui\Component\Listing\Column;

class FromYear implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var \Amasty\Deliverydate\Model\ResourceModel\Dinterval\Collection
     */
    protected $collection;

    /**
     * FromYear constructor.
     *
     * @param \Amasty\Deliverydate\Model\ResourceModel\Dinterval\CollectionFactory $dIntervalCollectionFactory
     */
    public function __construct(
        \Amasty\Deliverydate\Model\ResourceModel\Dinterval\CollectionFactory $dIntervalCollectionFactory
    ) {
        $this->collection = $dIntervalCollectionFactory->create();
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return $this->collection->getYearsAsArray();
    }
}
