<?php

namespace AHT\Question\Ui\Grid;

use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;
use Psr\Log\LoggerInterface as Logger;

class Collection extends SearchResult
{
    protected $_idFieldName = 'id';

    public function __construct(
        EntityFactory $entityFactory,
        Logger $logger,
        FetchStrategy $fetchStrategy,
        EventManager $eventManager,
        $mainTable = 'question',
        $resourceModel = 'AHT\Question\Model\ResourceModel\Question',
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
                ['table1join' => $this->getConnection()->getTableName('customer_entity')],
                'main_table.user_id = table1join.entity_id',
                array('*'))
                ->where("main_table.type = 0");
    }
}