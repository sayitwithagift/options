define(['df-lodash', 'jquery'], function(_, $) {return {_create: function() {
	var config = this.options.config;
	$('.product-custom-option').each(function() {
		var $e = $(this);
		var valueId = $e.val();
		var c = config[valueId];
		if (c) {
			var $a = $('<a>')
				.attr({
					class: 'MagicZoom'
					// 2018-07-17 https://www.magictoolbox.com/magiczoomplus/integration/#parameters
					,'data-options': _.map({
						zoomHeight: 560 // 2018-07-17 It is the same height as for the primary product image.
						,zoomPosition: 'left'
						,zoomWidth: 560 // 2018-07-17 It is the same width as for the primary product image.
					}, function(v, k) {return k + ': ' + v;}).join('; ')
					,href: c['full']
					// 2018-07-17 https://www.magictoolbox.com/magiczoomplus/integration/#acaption
					,title: $e.siblings('label').children('span:first').text()
				})
				.append(
					$('<img>')
						.attr({src: c['thumb']})
						.click(function() {$(this).siblings('.product-custom-option').click();})
			);
			$e.before($a);
			var ivl;
			var zInit = function() {
				if (MagicZoom) {
					MagicZoom.start($a.get(0));
					if (ivl) {
						clearInterval(ivl);
					}
				}
			};
			zInit();
			ivl = setInterval(zInit, 500);
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