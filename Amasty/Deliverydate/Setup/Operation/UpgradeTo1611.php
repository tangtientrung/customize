<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */


namespace Amasty\Deliverydate\Setup\Operation;

use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Amasty\Deliverydate\Model\ResourceModel\Deliverydate;
use Amasty\Deliverydate\Api\Data\DeliverydateInterface;

/**
 * Class UpgradeTo1611
 */
class UpgradeTo1611
{
    /**
     * @param SchemaSetupInterface $setup
     */
    public function execute(SchemaSetupInterface $setup)
    {
        $this->changeCommentColumn($setup);
    }

    /**
     * @param SchemaSetupInterface $setup
     */
    private  function changeCommentColumn(SchemaSetupInterface $setup)
    {
        $tableName = $setup->getTable(Deliverydate::MAIN_TABLE);

        $setup->getConnection()->changeColumn(
            $tableName,
            DeliverydateInterface::COMMENT,
            DeliverydateInterface::COMMENT,
            [
                'type' => Table::TYPE_TEXT,
                'length' => '64k',
                'nullable' => false
            ]
        );
    }
}
