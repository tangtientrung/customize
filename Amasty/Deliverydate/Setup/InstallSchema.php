<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */


namespace Amasty\Deliverydate\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Amasty\Deliverydate\Model\Deliverydate;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        /**
         * Create table 'amasty_amdeliverydate_deliverydate'
         */

        $table = $installer->getConnection()->newTable(
            $installer->getTable('amasty_amdeliverydate_deliverydate')
        )->addColumn(
            'deliverydate_id',
            Table::TYPE_INTEGER,
            8,
            ['unsigned' => true, 'identity' => true, 'nullable' => false, 'primary' => true]
        )->addColumn(
            'order_id',
            Table::TYPE_INTEGER,
            10,
            ['unsigned' => true, 'nullable' => false]
        )->addColumn(
            'increment_id',
            Table::TYPE_TEXT,
            50,
            ['nullable' => false]
        )->addColumn(
            'date',
            Table::TYPE_DATE,
            null,
            ['nullable' => false, 'default' => Deliverydate::RESETED_DATE_VALUE]
        )->addColumn(
            'time',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false]
        )->addColumn(
            'comment',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false]
        )->addColumn(
            'reminder',
            Table::TYPE_BOOLEAN,
            1,
            ['unsigned' => true, 'nullable' => false]
        )->addColumn(
            'tinterval_id',
            Table::TYPE_INTEGER,
            10,
            ['unsigned' => true, 'nullable' => true,  'default' => null]
        )->addColumn(
            'active',
            Table::TYPE_SMALLINT,
            6,
            ['nullable' => false, 'default' => '1']
        )->addIndex(
            $installer->getIdxName('amasty_amdeliverydate_deliverydate', ['order_id']),
            ['order_id']
        )->addIndex(
            $installer->getIdxName('amasty_amdeliverydate_deliverydate', ['tinterval_id']),
            ['tinterval_id']
        )->addForeignKey(
            $installer->getFkName('amasty_amdeliverydate_deliverydate', 'order_id', 'sales_order', 'entity_id'),
            'order_id',
            $installer->getTable('sales_order'),
            'entity_id',
            Table::ACTION_CASCADE
        );
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'amasty_amdeliverydate_dinterval'
         */

        $table = $installer->getConnection()->newTable(
            $installer->getTable('amasty_amdeliverydate_dinterval')
        )->addColumn(
            'dinterval_id',
            Table::TYPE_INTEGER,
            8,
            ['unsigned' => true, 'identity' => true, 'nullable' => false, 'primary' => true]
        )->addColumn(
            'store_ids',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false]
        )->addColumn(
            'from_year',
            Table::TYPE_SMALLINT,
            4,
            ['nullable' => false]
        )->addColumn(
            'from_month',
            Table::TYPE_SMALLINT,
            2,
            ['nullable' => false]
        )->addColumn(
            'from_day',
            Table::TYPE_SMALLINT,
            2,
            ['nullable' => false]
        )->addColumn(
            'to_year',
            Table::TYPE_SMALLINT,
            4,
            ['nullable' => false]
        )->addColumn(
            'to_month',
            Table::TYPE_SMALLINT,
            2,
            ['nullable' => false]
        )->addColumn(
            'to_day',
            Table::TYPE_SMALLINT,
            2,
            ['nullable' => false]
        )->addColumn(
            'description',
            Table::TYPE_TEXT,
            255,
            ['nullable' => true]
        );
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'amasty_amdeliverydate_holidays'
         */

        $table = $installer->getConnection()->newTable(
            $installer->getTable('amasty_amdeliverydate_holidays')
        )->addColumn(
            'holiday_id',
            Table::TYPE_INTEGER,
            8,
            ['unsigned' => true, 'identity' => true, 'nullable' => false, 'primary' => true]
        )->addColumn(
            'store_ids',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false]
        )->addColumn(
            'year',
            Table::TYPE_SMALLINT,
            4,
            ['nullable' => false]
        )->addColumn(
            'month',
            Table::TYPE_SMALLINT,
            2,
            ['nullable' => false]
        )->addColumn(
            'day',
            Table::TYPE_SMALLINT,
            2,
            ['nullable' => false]
        )->addColumn(
            'description',
            Table::TYPE_TEXT,
            255,
            ['nullable' => true]
        );
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'amasty_amdeliverydate_tinterval'
         */

        $table = $installer->getConnection()->newTable(
            $installer->getTable('amasty_amdeliverydate_tinterval')
        )->addColumn(
            'tinterval_id',
            Table::TYPE_INTEGER,
            8,
            ['unsigned' => true, 'identity' => true, 'nullable' => false, 'primary' => true]
        )->addColumn(
            'store_ids',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false]
        )->addColumn(
            'time_from',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false]
        )->addColumn(
            'time_from_sql',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false]
        )->addColumn(
            'time_to',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false]
        )->addColumn(
            'time_to_sql',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false]
        )->addColumn(
            'sorting_order',
            Table::TYPE_SMALLINT,
            5,
            ['unsigned' => true, 'nullable' => false]
        )->addColumn(
            'quota',
            Table::TYPE_INTEGER,
            10,
            ['unsigned' => true, 'default' => null]
        );
        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}
