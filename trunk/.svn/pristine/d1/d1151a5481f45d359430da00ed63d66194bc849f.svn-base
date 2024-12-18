<?php

/**
 *
 * @Author: Sushant Shinde
 * @Creation-Date: 20/11/2012
 * @Copyright: Mindgate Solution Pvt. Ltd.
 * @Description: This class is used for encryption / decryption 
 * @Program-Specs-Referred: Functional Specification and TAD documents
 * @Revision:
 * ----------------------------------------------------------------------------------------------------------------
 * @Version    | @Last Revision Date | @Name                  | @Function/Module affected    | @Modifications Done
 * ----------------------------------------------------------------------------------------------------------------	
 * 0.1			20/11/2012			  Sushant Shinde		   Initial Class and functionalities created
 *
 */
class EzeClickSecurity
{

	
/**
 * This method is used to get the encrypted data.
 *
 * @param String : Input
 * @param String : Key
 * @return String
 */	
function encryptValue($inputVal,$secureKey)
{
	$key='';
	for ($i=0; $i < strlen($secureKey)-1; $i+=2)
	{
		$key .= chr(hexdec($secureKey[$i].$secureKey[$i+1]));
	}
	
	$block = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
	$pad = $block - (strlen($inputVal) % $block);
	$inputVal .= str_repeat(chr($pad), $pad);
	
	$encrypted_text = bin2hex(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $inputVal, MCRYPT_MODE_ECB));
	
	return $encrypted_text;
}



/**
 * This method is used to get the decrypted data.
 *
 * @param String : Input
 * @param String : Key
 * @return String
 * 
 */
function decryptValue($inputVal,$secureKey)
{
	 $key='';
		for ($i=0; $i < strlen($secureKey)-1; $i+=2)
		{
			$key .= chr(hexdec($secureKey[$i].$secureKey[$i+1]));
		}
		
	$encblock='';
		for ($i=0; $i < strlen($inputVal)-1; $i+=2)
		{
			$encblock .= chr(hexdec($inputVal[$i].$inputVal[$i+1]));
		}

	$decrypted_text = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $encblock, MCRYPT_MODE_ECB);

		return $decrypted_text;

}



}
?>
