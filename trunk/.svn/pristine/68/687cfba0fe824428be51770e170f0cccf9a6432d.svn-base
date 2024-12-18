<?php 
define('AREA', 'A');
define('AREA_NAME', 'admin');

require  dirname(__FILE__) . '/../prepare.php';
require  dirname(__FILE__) . '/../init.php';

$response = '';

$time_limit_start = '21';
$time_limit_end = '09';
$current_time = date('H');

$mobile = preg_replace('/[^\d\s]/', '', $_REQUEST['mobile']);
$mobile = str_replace(' ', '', $mobile);
$trans_id = $_REQUEST['transaction_id'];
$variable = $_REQUEST['variable'];

if($mobile != '')
{
	$count = strlen($mobile);
	if($count == 12)
	{
		$prefix = substr($mobile, 0, 2);
		if($prefix != 91)
		{
			$response = 'Invalid Mobile No.';
		}
		else
		{
			$mobile = substr($mobile, 2);
		}
	}
	else if($count == 11)
	{
		$mobile = substr($mobile, 0, 1);
		if($prefix != 0)
		{
			$response = 'Invalid Mobile No.';
		}
		else
		{
			$mobile = substr($mobile, 1);
		}
	}
	else if($count == 10)
	{
		$prefix = substr($mobile, 0, 1);
		if($prefix == 0)
		{
			$response = 'Invalid Mobile No.';
		}
		else
		{
			$mobile = $mobile;
		}
		
		$prefix = substr($mobile, 0, 2);
		if($prefix == 91)
		{
			$response = 'Invalid Mobile No.';
		}
		else
		{
			$mobile = $mobile;
		}
	}
	else
	{
		$response = 'Invalid Mobile No.';
	}
}

if(($count = strlen($mobile)) == 10)
{
	$mobile = '91'.$mobile;
}
else
{
	$response = 'Invalid Mobile No.';
}

if($variable != '')
{
	$var = explode(':',$variable);
}


if($response == '')
{
	$template = db_get_row("select sender_id, template, restricted, total_limit, time_restriction from clues_sms_templates where id = '".$trans_id."'");

	if($template['time_restriction'] == 'Yes' && ($current_time > $time_limit_start || $current_time < $time_limit_end))
	{
		$total_entry = db_get_row("select count(template_id) as id from clues_sms_queue where template_id = '".$trans_id."' and variable = '".$variable."' and mobile = '".$mobile."'");
		
		if($total_entry['id'] < $template['total_limit'])
		{
			$sql = "insert into clues_sms_queue set template_id='".$trans_id."', variable='".$variable."', mobile='".$mobile."'";
			db_query($sql);
			$response = 'Inserted in queue';
		}
		else
		{
			$response = 'Added in queue';
		}
	}
	else
	{
		if($template['restricted'] == 'Yes')
		{
			$duplicate_entry = db_get_row("select sent_count, retry_count from clues_sms_messages where template_id = '".$trans_id."' and variable = '".$variable."' and mobile = '".$mobile."'");
		}
		
		if(!isset($duplicate_entry['sent_count']) || $duplicate_entry['sent_count'] < $template['total_limit'])
		{	
			if(strstr($template['template'] , 'variable1'))
			{
				$template['template'] = str_replace('variable1', $var[0], $template['template']);						
			}
			if(isset($var[1]) && strstr($template['template'] , 'variable2'))
			{
				$template['template'] = str_replace('variable2', $var[1], $template['template']);						
			}
			if(isset($var[2]) && strstr($template['template'] , 'variable3'))
			{
				$template['template'] = str_replace('variable3', $var[2], $template['template']);						
			}
			if(isset($var[3]) && strstr($template['template'] , 'variable4'))
			{
				$template['template'] = str_replace('variable4', $var[3], $template['template']);						
			}
			
			$url = 'http://www.myvaluefirst.com/smpp/sendsms?username=shopclues&password=shop1clu&to='.$mobile.'&from='.$template['sender_id'].'&text='.$template['template'].'&dlr-mask=19&dlr-url';
						
			$url = str_replace(' ', '%20', $url);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_FAILONERROR,1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 200);
			$retValue = curl_exec($ch);  
			curl_close($ch);
			$response = $retValue;
			 
			if($response != '')
			{
				if($response == 'Sent.')
				{
					$status = 'S';
				}
				else
				{
					$status = 'F';
				}
				if(!isset($duplicate_entry['sent_count']))
				{
					$sql = "insert into clues_sms_messages set template_id='".$trans_id."', variable='".$variable."', actual_message='".$template['template']."', mobile='".$mobile."', response='".$response."', status='".$status."', sent_count='1'";
				}
				else
				{
					$sql = "update clues_sms_messages set response='".$response."', status='".$status."', actual_message='".$template['template']."', sent_count='".($duplicate_entry['sent_count']+1)."' where template_id='".$trans_id."' and variable='".$variable."' and mobile='".$mobile."'";
				}
				db_query($sql);		
			}
			else
			{
				$sql = "update clues_sms_messages set response='".$response."', status='U', actual_message='".$template['template']."', retry_count='".($duplicate_entry['retry_count']+1)."' where template_id='".$trans_id."' and variable='".$variable."' and mobile='".$mobile."'";
				db_query($sql);		
			}
		}
		else
		{
			$response = 'Message already sent.';
		}
	}
}
echo $response;

//localhost/shopclues_site/tools/sms.php?mobile=9950422065&transaction_id=7&variable=992181:992181:992181:992181
//smoke.shopclues.com/tools/sms.php?mobile=9950422065&transaction_id=7&variable=ver1:2:ver3:ver4
?>