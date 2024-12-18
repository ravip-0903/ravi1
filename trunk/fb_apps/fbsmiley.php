
<?php
//$text = $_REQUEST['title'];
$text = 'hi hows u :P:):(gu';
function parseSmiley($text){
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
        $text = str_replace(    
            $smiley,
            "<img src='smiley/{$img}' />",
            $text
        );
    }

    // Now only return it
    return $text;
}
	echo parseSmiley($text);
	?>
	