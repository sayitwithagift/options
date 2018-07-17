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
				)
			;
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
		var $this = $(this);
		var $choices = $this.children('.siwag-options-choice');
		var count = $choices.length;
		if (count) {
			var numInRow = Math.floor($this.width() / $choices.first().width());
			var numRows = Math.ceil(count / numInRow);
			var numVisibleRows = 1;
			var moreStep = 1;
			if (numVisibleRows < numRows) {
				var morePosition = function() {return numVisibleRows * numInRow;};
				var expandedChoices = function() {return $choices.filter(':lt(' + morePosition() + ')');};
				var collapsedChoices = function() {return $choices.not(expandedChoices());};
				var aMore = 'Show more choices…';
				var $more = $('<a>')
					.attr({class: 'siwag-options-more'})
					.click(function() {
						numVisibleRows += moreStep;
						expandedChoices().show();
						moveMoreLink();
					})
					.html(aMore)
				;
				var moveMoreLink = function() {
					numVisibleRows === numRows ? $more.hide() : $choices.eq(morePosition()).after($more);
				};
				moveMoreLink();
				collapsedChoices().hide();
			}
		}
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