		<!--	Included insert and connection file -->		
				
					<?php 
					include_once('conn.php');
					include_once('insert.php');
					?>
		<!--	Script to validate form before submit and retain its value -->		
		<script type="text/javascript">
				function validate()
				 {
					 var x=((document.forms["form1"]["title"].value) && (document.forms["form1"]["comment"].value));
						   if (x==null || x=="")
						   {
							 document.getElementById('errors').innerHTML= 'Please enter both fields before submit';
							 return false;
						  }
				 }
			</script>	
	<html>
	<head>
	<title>
	
	</title>

	<link rel="stylesheet" href="styles_fb.css" type="text/css" />
	</head>
	<body>
	<div style="padding:5px;">
	<img src="http://www.shopclues.com/images/skin/shopclues_logo.gif" alt="Logo ShopClues" />
	</div>
	<form id="form1" name="form1" method="post" action="index.php" onSubmit="return validate()" >
	  <div class="fb_entry_block">
      <div class="fb_entry_title">Title: <span style="color:#ff0000;">*</span>		   
			
	  </div>
	  <div class="fb_entry_tit_fill">
	  <input name="title" type="text" id="title" value=""/>
	  </div>
	  <div style="clear:both;"></div>
	  <div class="fb_entry_title">Details: <span style="color:#ff0000;">*</span>	
	  </div>
		<div class="fb_entry_tit_fill">
		<textarea name="comment"  id="comment"></textarea>
		</div>
     </div>   
		<div class="fb_entry_title" style="border-right:1px solid dddddd; border-left:1px solid dddddd">
        <div style="clear:both;"></div>
		
		<input type="submit" class="button_fb_pro" name="submit" value="submit" />
		<div  style="display:inline-block; color:#ff0000;margin-left: 50px;" id="errors"></div>
		</div>

	</form>

	<?php
		include_once('display.php');
	?>
	</body>  
</html>

		