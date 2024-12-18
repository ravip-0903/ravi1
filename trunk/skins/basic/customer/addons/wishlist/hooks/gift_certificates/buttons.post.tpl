{* $Id: buttons.post.tpl 7135 2009-03-26 12:11:00Z zeke $ *}


{include file="addons/wishlist/views/wishlist/components/add_to_wishlist.tpl" but_name="dispatch[gift_certificates.wishlist_add]" but_onclick="return fn_check_amount();"}


<input type="hidden" name="result_ids" value="cart_status,wishlist" />