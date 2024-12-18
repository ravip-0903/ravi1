/*Written by Pankaj Jasoria | 29th Jan 2014 | Fixed Header*/
$(document).ready(function() {
    $(window).scroll(function() {
        var scroll = $(window).scrollTop();

        if ($(window).width() > 630) {

            if (scroll > 0) {
                $(".header_global").addClass('fixed');
                $(".our-logo").css({
                    'overflow': 'hidden',
                    'height': '48px'
                });
                $(".box_websitelinks").slideUp(1000);
                $(".box_customersupport").slideUp(1000);
                $(".user_merchant").slideUp(1000);
                $("#cart_data").css({
                    'background': '#0056a0 url(skins/basic/customer/images/NewSprite.gif) -171px -218px no-repeat'
                });
                $("#cart_status .box_cartstatus>a").css('color', '#fff');
                $("#cart_data .nl_new_luk_cart_no").css('color', '#0056a0');
            }
            if (scroll == 0) {
                $(".header_global").removeClass('fixed');
                $(".our-logo").removeAttr('style');
                $(".box_websitelinks").slideDown(50);
                $(".box_customersupport").slideDown(50);
                $(".user_merchant").slideDown(50);
                $("#cart_data").removeAttr('style');
                $("#cart_status .box_cartstatus>a").removeAttr('style');
                $("#cart_data .nl_new_luk_cart_no").removeAttr('style');
            }
        }
    });
});
