<style>
    .ifrm_bd body{padding:0; margin:0;}
</style>
    <?php
        //to validate that user is redirected to which page..
            include_once('conn.php');
            //THis below code is used to bypass security in IE8.
         header("p3p: CP=\"ALL DSP COR PSAa PSDa OUR NOR ONL UNI COM NAV\"");
         $fan_page = Registry::get('config.facebook_fan_page');
         ////'https://www.facebook.com/Shopclu/app_190322544333196';
         
        //Registry::get('config.facebook_fan_page');
        $time = time();
        if(isset($_REQUEST['state']) && isset($_REQUEST['code']))
        {
            header('location:'.$fan_page);
        }
        if(isset($_REQUEST['error_reason']) && isset($_REQUEST['error']) && isset($_REQUEST['error_description']))
        {
            header('location:'.$fan_page);
        }
        //to create connection and login url..

       // $login_url = $facebook->getLoginUrl(array('scope' => 'email,user_birthday'));
        //to validate user on basis of its userid.
        if($user_id)
        {
            include_once('user.php');
            echo "<iframe src='https://images.shopclues.com/images/mailer/facebook/me/vip_offers.html?i=$time' width='100%' class='ifrm_bd' style='border:0; overflow-y: hidden; height:2000px;'></iframe>";
        }
        else
        {   
               $login_url = $facebook->getLoginUrl(array('scope' => 'email,user_birthday')); 
        echo "<script type='text/javascript'>top.location.href='".$login_url."';</script>";
        exit; 
            //include_once('vip_login.html');
        }
     ?>
<script type="text/javascript">
 
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-27831792-1']);
  _gaq.push(['_setDomainName', 'shopclues.com']);
  _gaq.push(['_trackPageview']);
 
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
 
</script>