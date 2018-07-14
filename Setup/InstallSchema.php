<?php
namespace SayItWithAGift\Options\Setup;
use Magento\Framework\DB\Adapter\AdapterInterface as IA;
use Magento\Framework\DB\Ddl\Table as T;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
class InstallSchema implements InstallSchemaInterface {
	/**
	 * 2018-04-10
	 * @param SchemaSetupInterface $setup
	 * @param ModuleContextInterface $context
	 * @throws \Zend_Db_Exception
	 */
	function install(SchemaSetupInterface $setup, ModuleContextInterface $context) {
		$installer = $setup;
		$installer->startSetup();
		if (!$installer->tableExists('sayitwithagift_options')) {
			$table = $installer->getConnection()
				->newTable($installer->getTable('sayitwithagift_options'))
				->addColumn('oi_value_id', T::TYPE_INTEGER, null
					,['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned'=> true]
					,'OptionImages Value Id'
				)
				->addColumn('option_type_id', T::TYPE_INTEGER, null
					,['nullable' => false, 'unsigned' => true]
					,'Option Type Id'
				)
				->addColumn('image', T::TYPE_TEXT, 255, ['default' => '', 'nullable' => false], 'Image')
				->addIndex(
					$installer->getIdxName('sayitwithagift_options', ['option_type_id'], IA::INDEX_TYPE_UNIQUE)
					,['option_type_id'], ['type' => IA::INDEX_TYPE_UNIQUE]
				)
				->addForeignKey(
					$installer->getFkName(
						'sayitwithagift_options'
						,'option_type_id'
						,'catalog_product_option_type_value'
						,'option_type_id'
					)
					,'option_type_id'
					,$installer->getTable('catalog_product_option_type_value')
					,'option_type_id'
					,T::ACTION_CASCADE
					,T::ACTION_CASCADE
				)
				->setComment('OptionImages Value')
			;
			$installer->getConnection()->createTable($table);
		}
		$setup->endSetup();
	}
}