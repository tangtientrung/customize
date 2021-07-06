<?php

namespace AHT\Question\Ui\Grid;

use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;
use Psr\Log\LoggerInterface as Logger;

class AdminAnswer extends SearchResult
{
    protected $_idFieldName = 'id';
    /**
     * @var _coreSession
     */
    protected $_coreSession;

    public function __construct(
        EntityFactory $entityFactory,
        Logger $logger,
        FetchStrategy $fetchStrategy,
        EventManager $eventManager,
        $mainTable = 'question',
        $resourceModel = 'AHT\Question\Model\ResourceModel\Question',
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
        $question_id = $request[9];
        parent::_initSelect();

        // Join the 2nd Table
        $this->getSelect()
                ->where("main_table.user_id = 0")
                ->where("main_table.question_id = ".$question_id);
                
    }
}