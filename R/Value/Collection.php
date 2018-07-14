<?php
namespace SayItWithAGift\Options\R\Value;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection as P;
use SayItWithAGift\Options\M\Value as M;
use SayItWithAGift\Options\R\Value as R;
class Collection extends P {
	/**
	 * 2018-04-09
	 * @override
	 * @see P::_construct()
	 */
	function _construct() {$this->_init(M::class, R::class);}
}