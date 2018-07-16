<?php
namespace SayItWithAGift\Options\Plugin\Catalog\Ui\DataProvider\Product\Form\Modifier;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\CustomOptions as Sb;
use Magento\Ui\Component\Container;
use Magento\Ui\Component\Form\Element\DataType\Text;
use Magento\Ui\Component\Form\Element\Input;
use Magento\Ui\Component\Form\Field;
// 2018-04-09
final class CustomOptions {
	/**
	 * 2018-04-09
	 * @param \SayItWithAGift\Options\M\Value $oiValue
	 * @param \Magento\Catalog\Model\Locator\LocatorInterface $locator
	 * @param \Magento\Catalog\Helper\Image $imageHelper
	 * @param \Magento\Framework\UrlInterface $urlBuilder
	 */
	function __construct(
		\SayItWithAGift\Options\M\Value $oiValue,
		\Magento\Catalog\Model\Locator\LocatorInterface $locator, 
		\Magento\Catalog\Helper\Image $imageHelper,
		\Magento\Framework\UrlInterface $urlBuilder                             
	) {
		$this->_imageHelper = $imageHelper;
		$this->_oiValue = $oiValue;    
		$this->_urlBuilder = $urlBuilder;                               
		$this->locator = $locator;
	}

	/**
	 * 2018-04-09
	 * @param Sb $subject
	 * @param $meta
	 * @return mixed
	 */
	function afterModifyMeta(Sb $subject, $meta) {
		//$k1 = 'custom_options/children/options/children/record/children/container_option/children/';
		//$type = dfa_deep($meta, $k1.'container_common/children/type/arguments/data/config/formElement');
		if (isset($meta
			[$subject::GROUP_CUSTOM_OPTIONS_NAME]['children'][$subject::GRID_OPTIONS_NAME]['children']
			['record']['children'][$subject::CONTAINER_OPTION]['children'][$subject::GRID_TYPE_SELECT_NAME]
			['children']['record']['children']
		)) {
			$a =& $meta
				[$subject::GROUP_CUSTOM_OPTIONS_NAME]['children'][$subject::GRID_OPTIONS_NAME]['children']
				['record']['children'][$subject::CONTAINER_OPTION]['children'][$subject::GRID_TYPE_SELECT_NAME]
				['children']['record']['children']
			;
			// 2018-07-15
			// The sort ordering should be `45` here for Magento 2.1.x.
			// (as in the original `Pektsekye_OptionImages` module).
			// The same value I use in the qmigroupinc.store (Magento 2.1.8 based) website.
			// In the albumenvy.com (Magento 2.2.2 based) website I use the `21` value.
			$this->array_insert($a, 4, ['image' => $this->getOiImageFieldConfig(45)]);
			$b =& $meta[$subject::GROUP_CUSTOM_OPTIONS_NAME]['children'][$subject::GRID_OPTIONS_NAME];
			$b['arguments']['data']['config']['pageSize'] = 1000;
			$b
				['children']['record']['children'][$subject::CONTAINER_OPTION]['children']
				[$subject::GRID_TYPE_SELECT_NAME]['arguments']['data']['config']['pageSize']
					= 1000
			;
		}
		// 2018-07-15 I have not changed the original `40` value.
		$meta[$subject::GROUP_CUSTOM_OPTIONS_NAME]['children']['oi_js_code'] = $this->getHeaderContainerConfig(40);
		return $meta;
	}

	/**           
	 * 2018-04-09
	 * @param Sb $subject
	 * @param $data
	 * @return array
	 */
	function beforeModifyData(Sb $subject, $data) {
		$product = $this->locator->getProduct();
		$options = [];
		$images = $this->_oiValue->getImages((int)$product->getId());
		foreach ((array)$product->getOptions() as $index => $option) {
			$values = $option->getValues() ?: [];
			foreach ($values as $value) {
				$image = '';
				$imageUrl = '';
				if (isset($images[$value->getOptionTypeId()])) {
					$image = $images[$value->getOptionTypeId()];
					$imageUrl = $this->_imageHelper->init(
						$product
						,'product_page_image_small'
						,array('type'=>'thumbnail')
					)->resize(40)->setImageFile($image)->getUrl();
				}
				$options[$index][$subject::GRID_TYPE_SELECT_NAME][] = [
					'image' => $imageUrl, 'imageSavedAs' => $image
				];
			}
		}
		$data = array_replace_recursive($data, [
			$product->getId() => [
				$subject::DATA_SOURCE_DEFAULT => [
					$subject::FIELD_ENABLE => 1,
					$subject::GRID_OPTIONS_NAME => $options
				]
			]
		]);
		return [$data];
	}

	/**
	 * 2018-04-09
	 * @used-by afterModifyMeta()
	 * @param $array
	 * @param $position
	 * @param $insert_array
	 */
	private function array_insert(&$array, $position, $insert_array) {
		$first_array = array_splice ($array, 0, $position);
		$array = array_merge ($first_array, $insert_array, $array);
	}

	/**
	 * 2018-04-09
	 * @used-by afterModifyMeta()
	 * @param $sortOrder
	 * @return array
	 */
	private function getHeaderContainerConfig($sortOrder) {return [
		'arguments' => [
			'data' => [
				'config' => [
					'componentType' => Container::NAME,
					'content' => '',
					'formElement' => Container::NAME,
					'idColumn' => 'SayItWithAGift_Options',
					'label' => null,
					'sortOrder' => $sortOrder,
					'template' => "SayItWithAGift_Options/form/components/js"
				],
			],
		],
	];}

	/**
	 * 2018-04-09
	 * @used-by afterModifyMeta()
	 * @param $sortOrder
	 * @return array
	 */
	private function getOiImageFieldConfig($sortOrder) {return [
		'arguments' => [
			'data' => [
				'config' => [
					'label' => __('Image'),
					'additionalClasses' => 'oi-image-column',
					'componentType' => Field::NAME,
					'formElement' => Input::NAME,
					'elementTmpl' => "SayItWithAGift_Options/form/element/input_image",
					'dataScope' => 'image',
					'dataType' => Text::NAME,
					'sortOrder' => $sortOrder,
					'deleteImageText' => __('Delete Image'),
					'browseFilesText' => __('Browse Files...'),
					'uploadUrl' => $this->_urlBuilder->addSessionParam()->getUrl(
						'catalog/product_gallery/upload'
					),
					'imports' => [
						'image' => '${ $.provider }:${ $.parentScope }.image',
						'imageSavedAs' => '${ $.provider }:${ $.parentScope }.imageSavedAs'
					]
				],
			],
		]
	];}

	/**
	 * 2018-04-09
	 * @used-by __construct()
	 * @used-by beforeModifyData()
	 * @var \Magento\Catalog\Helper\Image
	 */
	private $_imageHelper;

	/**
	 * 2018-04-09
	 * @used-by __construct()
	 * @used-by beforeModifyData()
	 * @var \SayItWithAGift\Options\M\Value
	 */
	private $_oiValue;

	/**
	 * 2018-04-09
	 * @used-by __construct()
	 * @used-by getOiImageFieldConfig()
	 * @var \Magento\Framework\UrlInterface
	 */
	private $_urlBuilder;

	/**
	 * 2018-04-09
	 * @used-by __construct()
	 * @used-by beforeModifyData()
	 * @var \Magento\Catalog\Model\Locator\LocatorInterface
	 */
	private $locator;
}