<?php

namespace AHT\ProductOrderGrid\Ui\Grid;

use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;
use Psr\Log\LoggerInterface as Logger;

class Order extends SearchResult
{
    protected $_idFieldName = 'entity_id';
    /**
     * @var _coreSession
     */
    protected $_coreSession;

    public function __construct(
        EntityFactory $entityFactory,
        Logger $logger,
        FetchStrategy $fetchStrategy,
        EventManager $eventManager,
        $mainTable = 'sales_order',
        $resourceModel = 'Magento\Sales\Model\ResourceModel\Order',
        $identifierName = null,
        $connectionName = null,
        \Magento\Framework\Session\SessionManagerInterface  $coreSession
    ) {
        $this->_coreSession = $coreSession;
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $mainTable, $resourceModel, $identifierName, $connectionName);
    }

    /**
     * @return Collection|void
     */
    protected function _initSelect()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $request = $objectManager->get('Magento\Framework\App\Request\Http');
        $request = $request->getServer('HTTP_REFERER');
        $request =explode('/',$request);
        $id = $request[9];
        // $id = $this->getRequest()->getParam('id');
        // var_dump($id);
        // die();
        parent::_initSelect();

        // Join the 2nd Table
        $this->getSelect()
            ->join(
            ['table1join' => $this->getConnection()->getTableName('sales_order_item')],
            'main_table.entity_id = table1join.order_id',
            array('*'))
                ->where("main_table.state != 'Complete'")
                ->where("main_table.state != 'Close'")
                // ->where("main_table.user_id = 0")
                ->where("table1join.product_id = ".$id);
        // var_dump($this->getSelect());
        // die();
                
    }
}