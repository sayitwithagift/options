<?php
namespace SayItWithAGift\Options\M;
use Magento\Framework\Exception\LocalizedException as LE;
use Magento\Framework\Model\AbstractModel as P;
use SayItWithAGift\Options\R\Value as R;
use SayItWithAGift\Options\R\Value\Collection as RC;
// 2018-04-09
/** @method R getResource() */
class Value extends P {
	/**
	 * 2018-04-09
	 * @override
	 * @see P::__construct()
	 * @param \Magento\Framework\Model\Context $context
	 * @param \Magento\Framework\Registry $registry
	 * @param R $resource
	 * @param RC $resourceCollection
	 * @param array $data
	 */
    function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        R $resource,
        RC $resourceCollection,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

	/**
	 * 2018-04-09
	 * @param $productId
	 * @return mixed
	 * @throws LE
	 */
	function getImages($productId) {return $this->getResource()->getImages($productId);}
}