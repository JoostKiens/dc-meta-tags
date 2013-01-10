/*jshint forin:true, noarg:true, noempty:true, eqeqeq:true, bitwise:true, strict:true, undef:true, unused:true, curly:true, browser:true, jquery:true, indent:4, maxerr:50 */

jQuery(function ($) {
	"use strict";

	jQuery('.repeatable-add').click(function() {
		var field = jQuery(this).closest('td').find('.custom_repeatable li:last').clone(true),
			fieldLocation = jQuery(this).closest('td').find('.custom_repeatable li:last');

		jQuery('input', field).val('').attr('name', function(index, name) {
			return name.replace(/(\d+)/, function(fullMatch, n) {
				return Number(n) + 1;
			});
		});

		field.insertAfter(fieldLocation, jQuery(this).closest('td'));

		// Show remove buttons
		jQuery(this).closest('td').find('.repeatable-remove').show();
		return false;
	});

	jQuery('.repeatable-remove').click(function(){
		var parentUl = jQuery(this).closest('ul');
		jQuery(this).parent().remove();
		// If only one left, hide remove button
		
		if (parentUl.find('li').size() === 1) {
			parentUl.find('.repeatable-remove').hide();
		}
		return false;
	});

});