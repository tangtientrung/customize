<?php
 
 namespace AHT\Sa\Setup\Patch\Data;
  
 use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
 use Magento\Eav\Setup\EavSetup;
 use Magento\Eav\Setup\EavSetupFactory;
 use Magento\Framework\Setup\ModuleDataSetupInterface;
 use Magento\Framework\Setup\Patch\DataPatchInterface;
  
 class AddProductAttribute implements DataPatchInterface
 {
     /**
      * ModuleDataSetupInterface
      *
      * @var ModuleDataSetupInterface
      */
     private $moduleDataSetup;
  
     /**
      * EavSetupFactory
      *
      * @var EavSetupFactory
      */
     private $eavSetupFactory;
  
     /**
      * AddProductAttribute constructor.
      *
      * @param ModuleDataSetupInterface  $moduleDataSetup
      * @param EavSetupFactory           $eavSetupFactory
      */
     public function __construct(
         ModuleDataSetupInterface $moduleDataSetup,
         EavSetupFactory $eavSetupFactory
     ) {
         $this->moduleDataSetup = $moduleDataSetup;
         $this->eavSetupFactory = $eavSetupFactory;
     }
  
     /**
      * {@inheritdoc}
      */
     public function apply()
     {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $eavSetup->addAttribute('catalog_product', 'sale_agent_id', [
            'type' => 'text',
            'label' => 'Sale agent id',
            'input' => 'select',
            'source' =>'AHT\Sa\Model\Source\SaleAgent',
            'global' => ScopedAttributeInterface::SCOPE_STORE,
            'visible' => true,
            'used_in_product_listing' => true,
            'user_defined' => true,
            'required' => false,
            'group' => 'General',
            'sort_order' => 300,
        ]);
        $eavSetup->addAttribute('catalog_product', 'commission_type', [
            'type' => 'int',
            'label' => 'Commission type',
            'input' => 'select',
            'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Table',
            'required' => false,
            'visible' => true,
            'user_defined' => true,
            'position' => 101,
            'system' => 0,
            'option' =>
                array (
                    'values' =>
                        array (
                            0 => 'Fixed',
                            1 => 'Percent',
                        ),
                ),
            'default' => 0,
            'global' => ScopedAttributeInterface::SCOPE_STORE,
            'used_in_product_listing' => true,
            'group' => 'General',
            'sort_order' => 310,
        ]);
        $eavSetup->addAttribute('catalog_product', 'commission_value', [
            'type' => 'int',
            'label' => 'Commission value',
            'input' => 'text',
            'global' => ScopedAttributeInterface::SCOPE_STORE,
            'visible' => true,
            'used_in_product_listing' => true,
            'user_defined' => true,
            'required' => false,
            'group' => 'General',
            'sort_order' => 320,
        ]);
     }
  
     /**
      * {@inheritdoc}
      */
     public static function getDependencies()
     {
         return [];
     }
  
     /**
      * {@inheritdoc}
      */
     public function getAliases()
     {
         return [];
     }
  
     public static function getVersion()
     {
         return '1.0.1';
     }
 }
 