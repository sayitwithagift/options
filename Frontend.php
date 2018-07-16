<?php
namespace SayItWithAGift\Options;
use Df\Core\Exception as DFE;
use Magento\Catalog\Model\Product as P;
use Magento\Framework\Exception\LocalizedException as LE;
use Magento\Framework\View\Element\AbstractBlock as _P;
use SayItWithAGift\Options\R\Value as Rc;
// 2018-04-09
/** @final Unable to use the PHP «final» keyword here because of the M2 code generation. */
class Frontend extends _P {
	/**
	 * 2018-07-16
	 * @override
	 * @see _P::_toHtml()
	 * @used-by _P::toHtml():
	 *		$html = $this->_loadCache();
	 *		if ($html === false) {
	 *			if ($this->hasData('translate_inline')) {
	 *				$this->inlineTranslation->suspend($this->getData('translate_inline'));
	 *			}
	 *			$this->_beforeToHtml();
	 *			$html = $this->_toHtml();
	 *			$this->_saveCache($html);
	 *			if ($this->hasData('translate_inline')) {
	 *				$this->inlineTranslation->resume();
	 *			}
	 *		}
	 *		$html = $this->_afterToHtml($html);
	 * https://github.com/magento/magento2/blob/2.2.0/lib/internal/Magento/Framework/View/Element/AbstractBlock.php#L643-L689
	 * @return string
	 * @throws DFE|LE
	 */
	final protected function _toHtml() {
		$p = df_registry('product'); /** @var P $p */
		$rc = df_o(Rc::class); /** @var Rc $rc */
		$config = ['img' => []];
		$images = $rc->getImages((int)$p->getId());
		foreach ($images as $id => $image) {
			$valueId = (int)$id;
			$config['img'][$valueId] = df_catalog_image_h()->init(
				$p
				,'product_page_image_small'
				,['type' => 'thumbnail']
			)->resize(100)->setImageFile($image)->getUrl();
		}
		return !$images ? '' : df_cc_n(
			df_tag('script', ['type' => 'text/x-magento-init'], df_json_encode([
				'#product_addtocart_form' => ['siwagOptions' => ['config' => $config]]
			]))
			,df_link_inline(df_asset_name(null, $this, 'css'))
		);
	}
}