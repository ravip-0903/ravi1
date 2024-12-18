
{*seller connect link created by Raj Kumar Singh on 28th Feb 2013*}

{* {if empty($smarty.session.auth.user_id)}

{assign var="url" value='index.php?dispatch=products.seller_connect&product_id='|cat:$smarty.request.product_id}

<a href='index.php?dispatch=auth.login_form&return_url={$url|urlencode}'>{$lang.seller_connect_link}</a>

{else} *}
{assign var="product_id_enc" value=$smarty.request.product_id|base64_encode}
{assign var="url" value='index.php?dispatch=products.seller_connect&product_id='|cat:$product_id_enc}
<a class="fb-popup-login_new" rev='{$url}' href='index.php?dispatch=auth.fb_login&product_id={$smarty.request.product_id|base64_encode}&return_url={$url|urlencode}'>{$lang.seller_connect_link}</a>
{*{/if}*} 

<div style="display:none;" id="fb_login_popup">
	<div class="frm_blk_main_div">
		{include file="views/auth/fb_login.tpl"}
	</div>
</div>
{literal}

<script>
	$(document).ready(function(){
		$('.fb-popup-login_new').click(function(){
			var retu_url = $(this).attr("rev");
			$('#ajax_loading_box').css('display','block');

			$.ajax({
				type: "POST",
				url: 'index.php?dispatch=auth.fb_login',
				data:{data:'valid_ses',retur_url:retu_url}
			})
			.done(function( msg ) {
				$('#ajax_loading_box').css('display','none');
				if(msg==1)
				{

					window.location.href=retu_url;
				}
				else
				{
                    {/literal}
                    {if $config.isResponsive}
                    {literal}
                    window.location.href=retu_url;
                    {/literal}    
                        {else}
                    {literal}
                        $('#fb_login_popup').show();
                    {/literal}
                        {/if}
                    {literal}

				}					
			});	
			return false;
		});
	});
</script>
{/literal}
