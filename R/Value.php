<?php
namespace SayItWithAGift\Options\R;
use Magento\Framework\Exception\LocalizedException as LE;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb as P;
class Value extends P {
	/**
	 * 2018-04-09
	 * @override
	 * @see P::_construct()
	 */
	function _construct() {$this->_init('sayitwithagift_options', 'oi_value_id');}

	/**
	 * 2018-04-09
	 * @param $oldOptionId
	 * @param $newOptionId
	 * @throws LE
	 */
	function duplicate($oldOptionId, $newOptionId) {
		$productOptionValueTable = $this->getTable('catalog_product_option_type_value');
		$select = $this->getConnection()->select()
			->from($productOptionValueTable, array('option_type_id'))
			->where('option_id=?', $oldOptionId)
		;
		$oldTypeIds = $this->getConnection()->fetchCol($select);
		$select = $this->getConnection()->select()
			->from($productOptionValueTable, array('option_type_id'))
			->where('option_id=?', $newOptionId)
		;
		$newTypeIds = $this->getConnection()->fetchCol($select);
		foreach ($oldTypeIds as $ind => $oldTypeId) {
			$sql =
				'REPLACE INTO `' . $this->getMainTable() . '` '
				. 'SELECT NULL, ' . $newTypeIds[$ind] . ', `image`'
				. 'FROM `' . $this->getMainTable() . '` WHERE `option_type_id`=' . $oldTypeId
			;
			$this->getConnection()->query($sql);
		}
	}

	/**
	 * 2018-04-09
	 * @param $productId
	 * @return array
	 * @throws LE
	 */
	function getImages($productId) {
		$select = $this->getConnection()->select()
			->from(['cp' => $this->getTable('catalog_product_entity')], [])
			->join(['ca' => $this->getTable('catalog_product_option')], 'ca.product_id = cp.entity_id', [])
			->join(
				['va' => $this->getTable('catalog_product_option_type_value')]
				,'va.option_id = ca.option_id', ['option_type_id']
			)
			->join(
				['oi' => $this->getMainTable()]
				,"oi.option_type_id = va.option_type_id AND oi.image != ''" , ['image']
			)
			->where('cp.entity_id = ?', $productId)
		;
		return $this->getConnection()->fetchPairs($select);
	}

	/**
	 * 2018-04-09
	 * @param $productIds
	 * @return array
	 * @throws LE
	 */
	function getImagesOfProducts($productIds) {
		$r = [];
		if ($productIds) {
			$select = $this->getConnection()->select()
				->from(['cp' => $this->getTable('catalog_product_entity')], ['entity_id'])
				->join(['ca' => $this->getTable('catalog_product_option')], 'ca.product_id = cp.entity_id', [])
				->join(
					['va' => $this->getTable('catalog_product_option_type_value')]
					,'va.option_id = ca.option_id', ['option_type_id']
				)
				->join(
					['oi' => $this->getMainTable()]
					, "oi.option_type_id = va.option_type_id AND oi.image != ''", ['image']
				)
				->where('cp.entity_id IN (?)', $productIds)
			;
			foreach((array)$this->getConnection()->fetchAll($select) as $row){
				$r[$row['entity_id']][$row['option_type_id']] = $row['image'];
			}
		}
		return $r;
	}
}