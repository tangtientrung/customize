<?php

namespace AHT\ProductQuestion\Ui\Grid;

use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;
use Psr\Log\LoggerInterface as Logger;

class Collection extends SearchResult
{
    protected $_idFieldName = 'entity_id';

    public function __construct(
        EntityFactory $entityFactory,
        Logger $logger,
        FetchStrategy $fetchStrategy,
        EventManager $eventManager,
        $mainTable = 'product_question',
        $resourceModel = 'AHT\ProductQuestion\Model\ResourceModel\ProductQuestion',
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
        $this->addFilterToMap('entity_id', 'main_table.entity_id');

        parent::_initSelect();

        // Join the 2nd Table
        $this->getSelect()
        ->joinLeft('catalog_product_entity_varchar as pro','main_table.product_id = pro.entity_id AND pro.attribute_id = 73 ',array('value'));
    }
}