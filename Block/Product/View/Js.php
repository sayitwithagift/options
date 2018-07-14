<?php
namespace SayItWithAGift\Options\Block\Product\View;
use Magento\Framework\Exception\LocalizedException as LE;
// 2018-04-09
class Js extends \Magento\Framework\View\Element\Template {
	/**
	 * 2018-04-09
	 * @param \Magento\Framework\View\Element\Template\Context $context
	 * @param \SayItWithAGift\Options\M\Value $oiValue
	 * @param \Magento\Framework\Registry $coreRegistry
	 * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
	 * @param \Magento\Catalog\Helper\Image $imageHelper
	 * @param \Magento\Catalog\Model\Product\Media\Config $mediaConfig
	 * @param array $data
	 */
	function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
		\SayItWithAGift\Options\M\Value $oiValue,
		\Magento\Framework\Registry $coreRegistry,
		\Magento\Framework\Json\EncoderInterface $jsonEncoder,
		\Magento\Catalog\Helper\Image $imageHelper,
		\Magento\Catalog\Model\Product\Media\Config $mediaConfig,
		array $data = []
	) {
		$this->_oiValue = $oiValue;
		$this->_coreRegistry = $coreRegistry;
		$this->_jsonEncoder = $jsonEncoder;
		$this->_imageHelper = $imageHelper;
		$this->_mediaConfig = $mediaConfig;
		parent::__construct($context, $data);
	}

	/**
	 * 2018-04-09
	 * @return string
	 * @throws LE
	 */
	function getDataJson() {
		$config = ['img' => []];
		$images = $this->_oiValue->getImages((int)$this->getProduct()->getId());
		foreach ($images as $id => $image) {
			$valueId = (int)$id;
			$config['img'][$valueId] = $this->_imageHelper->init(
				$this->getProduct()
				,'product_page_image_small'
				,['type' => 'thumbnail']
			)->resize(66)->setImageFile($image)->getUrl();
		}
		return $this->_jsonEncoder->encode($config);
	}

	/**
	 * 2018-04-09
	 * @return mixed
	 */
	function getProduct() {
		if (!$this->hasData('product')) {
			$this->setData('product', $this->_coreRegistry->registry('product'));
		}
		return $this->getData('product');
	}

	protected $_oiValue;
	protected $_coreRegistry;
	protected $_jsonEncoder;
	protected $_imageHelper;
	protected $_mediaConfig;
}