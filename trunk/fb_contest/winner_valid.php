<?php
if(isset($_REQUEST['email_check1']))
    {
    
        include('conn.php');
         $shopclues_email = db_get_field("SELECT email FROM `cscart_users` where email ='".$_REQUEST['email_check1']."'");
        if(empty($shopclues_email) || !preg_match("/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$/i",$_REQUEST['email_check1']))
        {
            
            echo "wrong";
        }        
    }
?>