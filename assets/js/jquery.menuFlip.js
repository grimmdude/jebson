/**
* jQuery menuFlip plugin
*
* Copyright (c) 2011 Garrett Grimm (grimmdude.com)
* Dual licensed under the MIT and GPL licenses:
* http://www.opensource.org/licenses/mit-license.php
* http://www.gnu.org/licenses/gpl.html
*
*/

(function( $ ) {
	$.fn.menuFlip = function(options) {
		
		var settings = $.extend({
			'li_height'		: '20px',
			'flip_speed' 	: 150,
			'flipped_class' : 'flipped_item'
		}, options);

		// Set required CSS
		this.find('li')
		.css({
			'overflow' : 'hidden',
			'height' : settings.li_height
		})
		// When list item is hovered slide up to expose the flipped link
		.hover(function() {
			var negative_height = '-' + settings.li_height;
		
			$(this).find('a:first').animate({
				marginTop: negative_height
			}, settings.flip_speed);
		},
		function() {
			$(this).find('a:first').animate({
				marginTop: '0px'
			}, settings.flip_speed);
		})
		.find('a')
			.css({
				'display' 		: 'block',
				'line-height'	: settings.li_height
			})
			// For each list item set the child 'a' line height to match the li height and duplicate
			.each(function(){
				$(this)
					.clone()
					.appendTo($(this).parent())
					.addClass(settings.flipped_class);
			});
  	};
})( jQuery );