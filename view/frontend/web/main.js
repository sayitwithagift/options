define(['jquery'], function($) {return {_create: function() {
	var images = this.options.config.img;
	$('.product-custom-option').each(function() {
		var $e = $(this);
		var valueId = $e.val();
		if (images[valueId]) {
			$e.before($('<img>')
				.attr({src: images[valueId]})
				.click(function() {$(this).siblings('.product-custom-option').click();})
			);
			var $choice = $e.closest('.choice');
			$choice.addClass('siwag-options-choice');
			// 2018-05-05
			// «Hightlight the selected image-based custom option»
			// https://www.upwork.com/ab/f/contracts/19978962
			if ('radio' === $e[0].type) {
				$choice.wrapInner($('<div>').addClass('df-border'));
				$e.click(function() {
					var $choice = $e.closest('.choice');
					$choice.addClass('df-selected');
					$choice.siblings().removeClass('df-selected');
				});
			}
		}
	});
	var $optionsWrapper = $('.catalog-product-view #product-options-wrapper');
	$('.options-list', $optionsWrapper).each(function() {
		$('input', this).first().click();
	});
	$('select.product-custom-option', $optionsWrapper).each(function() {
		$('option', this).each(function() {
			if ('' !== this.value) {
				this.selected = true;
				return false;
			}
		});
	});
}};});