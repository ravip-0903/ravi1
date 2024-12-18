{** block-description:sdeep_payment_block **}

{if $product.price >= $config.emi_min_amount}
<div class="produ_detai_right_b_mng pj2_top_border" style="margin-top:14px">
<div class="pj2_prd_seller_name" style="font:bold 14px Arial, Helvetica, sans-serif; padding:2px 0 ; text-align: center; background:#048CCC; color:#fff;">{$lang.emi_block_title}</div>	 
		<ul class="trm_feture payment_mode_blk"style="padding:5px;">
                    <li style="font-family:trebuchet ms, ubuntu;">
                        {math assign="emi" equation="x*y/z" x=$product.price y=$product.emi_fee z=100}
                        {$lang.emi_sec_start}<span style="color:#009900">{if $emi eq 0}{$lang.emi_sec_mid1}{else}{$emi|ceil}{/if}</span>{$lang.emi_sec_mid2}<span style="color:#990000">{$lang.rs}
                                    
                                    {if $product.third_price eq 0}
                                        {math assign="result" equation="(x+x*y/z)/w" x=$product.price y=$product.emi_fee z=100 w=3}
                                        {$result|ceil|number_format}
                                    {else}
                                        {math assign="result" equation="(x+x*y/z)/w" x=$product.third_price y=$product.emi_fee z=100 w=3}
                                        {$result|ceil|number_format}
                                    {/if}
                                    {$lang.emi_sec_end}</span><a class="emi_cal ahover_nl" style="color: #048ccc;">{$lang.emi_block_read}</a></li>
                 </ul>
</div>
<div class="pj2_content_hide" id="calc" style="position:fixed; z-index:200; background:url(images/skin/background_for_banklist.png); left:0; top:0; width:100%; min-height:100%; display:none;" >
        <div style=" width:600px; margin:auto;">
        <div class="pj2_popup_prd" style="margin-top:144px; width:600px; min-height: 100px; white-space: normal;">
            <img class="img_close" src="http://images.shopclues.com/images/skin/pj2_close_btn_banklist.png "  />
            <h1 style="font-size:16px; padding:0 0 10px; color:#048ccc; text-align: center;">{$lang.emi_block_cal}</h1>
        <p style=" display:block; text-align:left; padding:0; margin:0px 0 0 10px;">
            <label style="float:left; margin-top:5px;">{$lang.emi_block_select_label}</label>&nbsp;&nbsp;&nbsp;
            <select style="margin-left:20px;" name="bank" id="bank" class="cm-ajax pj2_new_textbox" onchange="addperiod(this.value,{if $product.third_price eq 0}{$product.price}{else}{$product.third_price}{/if});" >
                <option value='' id='bank_select'>Select</option>
                {foreach from=$product.bank item='bankinfo'}
                    <option value="{$bankinfo.payment_option_id}">{$bankinfo.name}</option>    
                {/foreach}
            </select>
        </p> 
        <p id="emidata" style="line-height: 18px;text-align: justify; margin-top: 20px;">
        </p>
        <p>
        {$lang.emi_block_detail}
        </p>
</div></div> 
      </div>                                    
{literal}    
<script type="text/javascript">
$(document).ready(function(){
            $('.emi_cal').click(function(){
                $('#bank_select').attr('selected','selected');
                $('#emidata').html('');
                $("#calc").show();
                });
                    $('.img_close').click(function(){
                            $("#calc").hide();
                            });
});
function addperiod(id,price)
{  
        if(id == '')
            {
                $('#emidata').html('');
                return;
            }
        $('#emidata').html('{/literal}{$lang.emi_wait}{literal}');
        $.ajax({
            type : 'GET', // Using GET method to sent data
            url : 'index.php',
            dataType : 'text',  //return data type is JavaScript Object Notation
            data : {'dispatch' : 'products.period','payment_id' : id, 'price' : price },
            success : function(response) 
            { 	
               $('#emidata').html(response);
            }
       });          
}
</script>
<style type="text/css">
    .payment_mode_blk{list-style:none; font-size:12px; margin:0; padding:0;}
li.payment_mode_bg{background:url(http://cdn.shopclues.com/images/banners/payment_mode_icon_prd.gif) 5px 4px no-repeat;padding-left:25px!important; line-height: 21px; list-style:none;}
</style>
{/literal}
{/if}