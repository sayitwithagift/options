<?php
namespace SayItWithAGift\Options\Plugin\Catalog\Model\Product\Option;
use SayItWithAGift\Options\M\Value as sV;
use Magento\Catalog\Model\Product\Option\Value as Sb;
final class Value {
	function __construct(sV $v) {$this->_v = $v;}
	function aroundDuplicate(Sb $sb, \Closure $proceed, $oldOptionId, $newOptionId) {
		$result = $proceed($oldOptionId, $newOptionId);
		$this->_v->getResource()->duplicate($oldOptionId, $newOptionId);
		return $result;
	}
	private $_v;
}