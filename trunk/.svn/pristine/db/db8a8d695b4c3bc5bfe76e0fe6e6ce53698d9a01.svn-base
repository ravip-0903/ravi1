<!DOCTYPE >
<html xmlns="http://www.w3.org/1999/xhtml" debug="true">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <script type="text/javascript">
           function init() {
        	   Android.showToast('<?php echo $class?>');
           }		   
        </script>
        
          <style type="text/css">
            body{
                font:normal 30px trebuchet ms;
                text-align:center;
                padding-top:20%;
            }
            h1{
                color: #EE811D;
                font-size: 1em;                
            }
            div{
                color:#333;
            }
            
            form {
				
				margin-bottom:2em;
				
			}
            p{
                color: #0079a9;
                font-size: 0.8em;                
            }            
            input{
                background-color:#0587bb;
                border-radius:5px;
                padding:2% 5%;
                color:#fff;
                border:0px none;
                margin-bottom:20px;
                font-size:1em;
                outline:none;
                margin-top:20px;
                width:80%;
            }
            input[type=submit]{
                background-color:#EE811D;
                border-radius:5px;
                padding:2% 5%;
                color:#fff;
                border:0px none;
                margin-bottom:20px;
                font-size:1em;
                outline:none;                
                width:80%;
            }
     
            @media only screen and (max-width: 800px) {
 
            body{
                font:normal 20px trebuchet ms;
                text-align:center;
                padding-top:20%;
            }
            h1{
                color: #EE811D;
                font-size: 0.8em;                
            }
            div{
                color:#333;
            }
            
            form {
				
				margin-bottom:2em;
				
			}
            p{
                color: #0079a9;
                font-size: 0.8em;                
            }            
            input{
                background-color:#0587bb;
                border-radius:5px;
                padding:2% 5%;
                color:#fff;
                border:0px none;
                margin-bottom:20px;
                font-size:1em;
                outline:none;
                margin-top:20px;
                width:80%;
            }
            input[type=submit]{
                background-color:#EE811D;
                border-radius:5px;
                padding:2% 5%;
                color:#fff;
                border:0px none;
                margin-bottom:20px;
                font-size:1em;
                outline:none;                
                width:80%;
            } 
		}

		@media only screen and (max-width: 480px) {
		 
				    body{
				        font:normal 15px trebuchet ms;
				        text-align:center;
				        padding-top:10%;
				    }
				    h1{
				        color: #EE811D;
				        font-size: 1em;                
				    }
				    div{
				        color:#333;
				    }
				    
				    form {
				
						margin-bottom:2em;
				
					}
				    p{
				        color: #0079a9;
				        font-size: 0.8em;                
				    }            
				    input{
				        background-color:#0587bb;
				        border-radius:5px;
				        padding:2% 5%;
				        color:#fff;
				        border:0px none;
				        margin-bottom:20px;
				        font-size:1em;
				        outline:none;
				        margin-top:20px;
				        width:80%;
				    }
				    input[type=submit]{
				        background-color:#EE811D;
				        border-radius:5px;
				        padding:2% 5%;
				        color:#fff;
				        border:0px none;
				        margin-bottom:20px;
				        font-size:1em;
				        outline:none;                
				        width:80%;
				    }
		}
        </style>
        
    </head>
    <body>        
   		<h1><?php echo fn_get_lang_var('api_order_placed_heading');?></h1>
		<p><?php echo $msg?></p>
		
		<?php if($status == 'F'){?>		
			
			<form name="form1" id="form1" method="GET" action ="<?php echo $_SERVER['REQUEST_URI']?>">  
				<input type="submit" value ="Retry Payment" >
				<input type="hidden" name ="order_id"value ="<?php echo $order_id?>" >
				<input type="hidden" name ="key"value ="<?php echo Registry::get('config.api_static_key');?>" >
				<input type="hidden" name ="retry"value ="1" >
			</form>			
			
		<?php } ?>	
		
        <div>
          <input value="Continue Shopping" type="button" name="submit" id="btnSubmit" onclick="javascript:return init();" /> 
        </div>  
		


                     
    </body>
        
</html>
