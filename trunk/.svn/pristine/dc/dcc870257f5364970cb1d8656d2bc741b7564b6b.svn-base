<?php 
define('AREA', 'A');
define('AREA_NAME', 'admin');

require  dirname(__FILE__) . '/../prepare.php';
require  dirname(__FILE__) . '/../init.php';

$time_limit_start = '21';
$time_limit_end = '09';
$current_time = date('H');

$sms_per_cron_limit=50;

function log_to_file($file_name,$content) {
		$stringData = $content."\r\n";
    	$myFile = DIR_IMAGES.'logs/'.$file_name."_log.txt";
    	$fh = fopen($myFile, 'a') or die("can't open file");
    	fwrite($fh, $stringData);	
    	fclose($fh);
}

if(isset($_GET['priority']))
{
		  $entries = db_get_array("select clues_sms_templates.sender_id, clues_sms_templates.template, 		clues_sms_templates.restricted, clues_sms_templates.total_limit, clues_sms_templates.time_restriction,clues_sms_queue.* from clues_sms_templates,clues_sms_queue where clues_sms_templates.id=clues_sms_queue.template_id and clues_sms_queue.status = 'UNSENT' and clues_sms_templates.template_priority IN(".$_GET['priority'].") limit 0,".$sms_per_cron_limit."");
		 
  if(!empty($entries))
  {
	foreach($entries as $total)
	{
		db_query("update clues_sms_queue set status='PROCESSING' where id='".$total['id']."'");
		
		$response = '';
		$mobile = preg_replace('/[^\d\s]/', '', $total['mobile']);
		$mobile = str_replace(' ', '', $mobile);
		
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
				$prefix = substr($mobile, 0, 1);
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
		
		if($total['variable'] != '')
		{
			$var = explode(':',$total['variable']);
		}
	
		if($response == '')
		{
			
			if($current_time < $time_limit_start || $current_time > $time_limit_end)
			{
				if($total['restricted'] == 'Yes')
				{
					$duplicate_entry = db_get_row("select sent_count, retry_count from clues_sms_messages where template_id = '".$total['template_id']."' and variable = '".$total['variable']."' and mobile = '".$total['mobile']."'");
				}
				
				if(!isset($duplicate_entry['sent_count']) || $duplicate_entry['sent_count'] < $total['total_limit'])
				{	
					if(strstr($total['template'] , 'variable1'))
					{
						$total['template'] = str_replace('variable1', $var[0], $total['template']);						
					}
					if(strstr($total['template'] , 'variable2'))
					{
						$total['template'] = str_replace('variable2', $var[1], $total['template']);						
					}
					if(strstr($total['template'] , 'variable3'))
					{
						$total['template'] = str_replace('variable3', $var[2], $total['template']);						
					}
					if(strstr($total['template'] , 'variable4'))
					{
						$total['template'] = str_replace('variable4', $var[3], $total['template']);						
					}
					if(strstr($total['template'] , 'variable5'))
					{
						$total['template'] = str_replace('variable5', $var[4], $total['template']);						
					}
					
					$url = 'http://www.myvaluefirst.com/smpp/sendsms?username=shopclues&password=shop1clu&to='.$mobile.'&from='.$total['sender_id'].'&text='.$total['template'].'&dlr-mask=19&dlr-url';
				  
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
					$result = $retValue;
					echo $result."<br/>";
						
					$sql_queue = "update clues_sms_queue set status='PROCESSED' where id='".$total['id']."'";
					db_query($sql_queue);
					if($result != '')
					{
						if($result == 'Sent.')
						{
							$status = 'S';
							
						}
						else
						{
							$status = 'F';
							
						}
						
						$duplicate_entry_new = db_get_row("select sent_count, retry_count from clues_sms_messages where template_id = '".$total['template_id']."' and variable = '".$total['variable']."' and mobile = '".$total['mobile']."'");
						
						if(isset($duplicate_entry_new['sent_count']) && $duplicate_entry_new['sent_count']!= '')
						{
							$sql = "update clues_sms_messages set response='".$result."', status='".$status."', actual_message='".$total['template']."', sent_count='".($duplicate_entry_new['sent_count']+1)."' where template_id='".$total['template_id']."' and variable='".$total['variable']."' and mobile='".$total['mobile']."'";
						}
						else
						{
							$sql = "insert into clues_sms_messages set template_id='".$total['template_id']."', variable='".$total['variable']."', actual_message='".$total['template']."', mobile='".$total['mobile']."', response='".$result."', status='".$status."', sent_count='1'";
						}
						db_query($sql);		
					}
					else
					{
						$sql = "update clues_sms_messages set response='".$result."', status='U', actual_message='".$total['template']."', retry_count='".($duplicate_entry_new['retry_count']+1)."' where template_id='".$total['template_id']."' and variable='".$total['variable']."' and mobile='".$total['mobile']."'";
						db_query($sql);		
					}
				}
				else
				{
					$response = 'Message already sent.';
				}
			}
		}
		
		if($response!='')
		{
			$content="Error in sending template-".$total['template_id']." Error Message-".$response." Date-".date('d/m/Y H:i:s')." Variables-".$total['variable'];
			log_to_file('sms_error',$content);
			echo "LOGGED <br/>";
		}
	}
  }
  else
  {
	  echo "No SMS To Send";
	  exit;
  }
}
else
{
	exit;
}
?>