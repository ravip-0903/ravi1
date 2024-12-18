/*
 @author :- Raj Chaudhary
 @description :- A small plugin by Raj Chaudhary for converting numbers to star.
 @company :- Shopclues.com
 @date :- 18-11-2013
 */

$.fn.makeStars = function() {
    return $(this).each(function() {
        // Get the value
        var val = parseFloat($(this).html());
        // Make sure that the value is in 0 - 5 range, multiply to get width
        var size = Math.max(0, (Math.min(5, val))) * 16;
        // Create stars holder
        var $span = $('<span />').width(size);
        // Replace the numerical value with stars
        $(this).html($span);
    });
}