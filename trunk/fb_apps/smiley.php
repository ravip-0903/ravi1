    <?php

	function parseSmiley($title1,$comment1){
		// Smiley to image
		$smileys = array(
		';(' => 'crying.png',
		':D' => 'laugh.png',
		':(' => 'sad.png',
		':)' => 'smile.png',
		':P' => 'tongue out.png'
		);
		// Now you need find and replace
		foreach($smileys as $smiley => $img){
			$title1 = str_replace(    
				$smiley,
				"<img src='smiley/{$img}' />",
				$title1
			);
			$comment1 = str_replace(    
				$smiley,
				"<img src='smiley/{$img}' />",
				$comment1
			);
		}
		// Now only return it
		return $title1.$comment1;
	}
		?>