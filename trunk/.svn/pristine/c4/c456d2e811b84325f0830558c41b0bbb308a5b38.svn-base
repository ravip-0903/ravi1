{*{if empty($smarty.session.auth.user_id)}
    
    {assign var="url" value='index.php?dispatch=products.seller_connect&company_id='|cat:$smarty.request.company_id}

    <a style="float:right;margin:5px 7px 0 0;" href='index.php?dispatch=auth.login_form&return_url={$url|urlencode}'>{$lang.seller_connect_link}</a>
        
       {else}
  *}      
    <a class="ml_mobile_ask_a_mrcnht" style="float:right;margin:5px 7px 0 0;" href='index.php?dispatch=products.seller_connect&company_id={$smarty.request.company_id|base64_encode}'>{$lang.seller_connect_link}</a>
        
 {*{/if}*} 
