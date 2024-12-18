{literal}
    <style type="text/css">
.seller_connect_page_nl{width:970px; margin:auto;}
.seller_connect_page_nl .width_reg_form{width:630px; margin:65px auto 0;}
.seller_connect_page_nl .icon_left_blk{float:left; width:105px;}
.seller_connect_page_nl .icon_left_blk img{float:left; clear:both; margin:0 0 10px 0 ;}
.seller_connect_page_nl .regs_login_form{float:left; background:url(http://cdn.shopclues.com/images/banners/seller_connect_login_id_bg.jpg) no-repeat; width:465px; height:265px;}
.seller_connect_page_nl .regs_login_form img{margin:35px 0 0 46px;}
.seller_connect_input_id {float:left; clear:both; margin:30px 0 0 37px; width:390px; font-size:12px; }
.seller_connect_page_nl .seller_login_text{float:left; margin:20px 0 0 10px;}
.seller_connect_page_nl .input_fild{clear:both; width:210px; padding:0 7px; height:34px; background:url(http://cdn.shopclues.com/images/banners/seller_connect_input_bg.png); border:none; margin:4px 0 0 0; color:#bbbcbc;}
.seller_connect_page_nl .seller_login_text input:focus{border:none; outline:none; }
.seller_connect_input_btn{clear:both;}
.seller_connect_input_btn input{margin:20px 0 0 120px; background:url(http://cdn.shopclues.com/images/banners/seller_connect_input_btn.png) no-repeat; width:148px; height:24px; border:0; outline:none; line-height:30px; }
.seller_connect_pln_text{clear: both; margin:20px auto;width: 550px; text-align:center; color:#3c3b3b;}
</style>
    {/literal}
   
<div class="seller_connect_page_nl">
    <div><img src="http://cdn.shopclues.com/images/banners/seller_connect_banner.jpg" width="970" height="141" alt="Seller Summit June 2013"/></div>
    <div class="width_reg_form">
		<div class="icon_left_blk">
        	<img src="http://cdn.shopclues.com/images/banners/seller_connect_celebrates_icon.jpg" height="71" width="72" />
           <img src="http://cdn.shopclues.com/images/banners/seller_connect_music_fun_icon.jpg" height="71" width="72" />
            <img src="http://cdn.shopclues.com/images/banners/seller_connect_win_icon.jpg" height="71" width="72" />
        </div>  
        <div class="regs_login_form">
        	
            <div class="seller_connect_input_id">
               {$lang.registration_closed_text}
            </div>
       </div>
       
    <div class="seller_connect_pln_text">
<p>    ShopClues.com hosts The Seller Summit, an event full of interactions and fun that you surely don't want to miss.
 Be the part of this most awaited evening and meet our Marketing & Tech Experts to  make your business rock 
at ShopClues. </p>
    </div>
</div>
{literal}
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function(){
$('#id1').prop('disabled',false);
$('#id1_prop').on('click', function(){            
    $('#id1').prop('disabled',false);
         $('#id2').prop('disabled',true);
       
});
    
 $('#id2').prop('disabled',true);
$('#id2_prop').on('click', function(){            
       $('#id2').prop('disabled',false);
         $('#id1').prop('disabled',true);
       
});
    $('.central-column').addClass('login_full');
        $('.notification-e').css('float','left');
            $('.notification-n').css('float','left');
        
$('.cm-notification-close').click(function(){
                $('.notification-content').hide();
            });
        });
    </script>

    {/literal}