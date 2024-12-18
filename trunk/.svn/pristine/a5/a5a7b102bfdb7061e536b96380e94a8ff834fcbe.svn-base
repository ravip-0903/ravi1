<?php
// GET PARAMETERS "hour","onlysync","nottosync" all are not compulsory But "mode" is Compulsory.

define('AREA', 'A');
	define('AREA_NAME', 'admin');
require  dirname(__FILE__) . '/../prepare.php';
require  dirname(__FILE__) . '/../init.php';
if($_POST['sub']){
	if ($_FILES["file"]["type"] == "text/csv")
	{
		$filename = 'UPLOAD_SERVICE_AREA.csv';
		if ($_FILES["file"]["error"] > 0)
		{
			echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
		}
		else
		{				
			move_uploaded_file($_FILES["file"]["tmp_name"], $filename);
			fn_readandinsert_servicearea();
		}
	}
}
function fn_readandinsert_servicearea()
{
	$fp = fopen('UPLOAD_SERVICE_AREA.csv','r') or die("can't open file");
		$cnt = 0;
		while($res = fgetcsv($fp,1024)) {
			$cnt++;
			if($cnt==1)
			{
				if($res[0]=="Pincode" && $res[1]=="COD" && $res[2]=="Carrier")
				{
					continue;
				}
				else
				{
					echo "Format of csv is not correct.";
					break;
				}
			}
			$cod = 'N';
			if($res[1]=="Y")
			{
				$cod = 'Y';
			}
			$sql = "select id from clues_carriers_service_area where carrier_id='". $res[2] ."' and pincode='". $res[0] ."' and is_cod='". $cod ."'";
			if(!db_get_field($sql))
			{
				$sql = "select id from clues_carriers_service_area where carrier_id='". $res[2] ."' and pincode='". $res[0] ."'";
				$id = db_get_field($sql);
				if(!$id)
				{
					$sql = "insert into clues_carriers_service_area set carrier_id='". $res[2] ."',pincode='". $res[0] ."',is_cod='".$cod."'";
				}
				else
				{
					$sql = "update clues_carriers_service_area set is_cod='".$cod."' where id='". $id ."'";
				}
				db_query($sql);
				echo $sql . "<br>";
			}
			
		}
}
//fn_readandinsert_servicearea();
?>

<html>
<body>
<h1>Upload Service Area</h1>
Format - (must be a comma sep. CSV file)
<table>
<tr><th>Pincode</th><th>COD</th><th>Carrier</th></tr>
<tr><td>xxxx</td><td>Y</td><td>7</td></tr>
<tr><td>xxxx</td><td></td><td>7</td></tr>
<tr><td>xxxx</td><td>Y</td><td>7</td></tr>
</table>
<form name="form" method="post" enctype="multipart/form-data">
<input type="file" name="file" />
<input type="hidden" value="1" name="sub"/>
<input type="submit" name="submit" value="upload">
</form>
</body>
</html>