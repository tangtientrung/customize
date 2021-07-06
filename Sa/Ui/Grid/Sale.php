<?php

namespace AHT\Sa\Ui\Grid;

use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;
use Psr\Log\LoggerInterface as Logger;

class Sale extends SearchResult
{
    protected $_idFieldName = 'id';

    public function __construct(
        EntityFactory $entityFactory,
        Logger $logger,
        FetchStrategy $fetchStrategy,
        EventManager $eventManager,
        $mainTable = 'sa',
        $resourceModel = 'AHT\Sa\Model\ResourceModel\Sa',
        $identifierName = null,
        $connectionName = null
    ) {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $mainTable, $resourceModel, $identifierName, $connectionName);
    }

    /**
     * @return Collection|void
     */
    protected function _initSelect()
    {
        parent::_initSelect();

        // Join the 2nd Table
        $this->getSelect()
            ->join(
            ['table1join' => $this->getConnection()->getTableName('sales_order_item')],
            'main_table.order_item_id = table1join.item_id',
            array('*'))
            ->joinLeft('catalog_product_entity_varchar as table2join','table1join.product_id = table2join.entity_id AND table2join.attribute_id = 73 ',array('*'))
            ;
    }
}