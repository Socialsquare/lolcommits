<?php
error_reporting(-1);
ini_set('display_errors', 'On');

$config_str = file_get_contents("config.json");
$config = json_decode($config_str, true);
$config = array_merge(array(
	'slack_icon_emoji' => ':camera:',
	'image_dir' => 'commits',
	'message_font_file' => './OpenSans-Bold.ttf',
	'message_font_size' => 16.0,
	'message_text_color' => array(255, 255, 255),
	'message_stroke_color' => array(10, 10, 10),
	'message_stroke_size' => 2
), $config);

function slack_post_message($username, $message, $image_url) {
	global $config;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $config['slack_webhook_url']);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_VERBOSE, true);
	$options = array(
		CURLOPT_POST => 1,
		CURLOPT_POSTFIELDS => json_encode(array(
			'channel' => $config['slack_channel'],
			'username' => $username,
			'text' => $message,
			'icon_emoji' => $config['slack_icon_emoji'],
			'attachments' => array(array('image_url' => $image_url))
		))
	);
	curl_setopt_array($ch, $options);
	return curl_exec($ch) === 'ok';
}

/**
 * Writes the given text with a border into the image using TrueType fonts.
 * @author John Ciacia 
 * @param image An image resource
 * @param size The font size
 * @param angle The angle in degrees to rotate the text
 * @param x Upper left corner of the text
 * @param y Lower left corner of the text
 * @param textcolor This is the color of the main text
 * @param strokecolor This is the color of the text border
 * @param fontfile The path to the TrueType font you wish to use
 * @param text The text string in UTF-8 encoding
 * @param px Number of pixels the text border will be
 * @see http://us.php.net/manual/en/function.imagettftext.php
 * @see http://www.johnciacia.com/2010/01/04/using-php-and-gd-to-add-border-to-text/
 */
function imagettfstroketext(&$image, $size, $angle, $x, $y, &$textcolor, &$strokecolor, $fontfile, $text, $px) {
 
		for($c1 = ($x-abs($px)); $c1 <= ($x+abs($px)); $c1++)
				for($c2 = ($y-abs($px)); $c2 <= ($y+abs($px)); $c2++)
						$bg = imagettftext($image, $size, $angle, $c1, $c2, $strokecolor, $fontfile, $text);
 
	 return imagettftext($image, $size, $angle, $x, $y, $textcolor, $fontfile, $text);
}

function process_image_files($hash, $message, $image_files) {
	global $config;
	$animation = new Imagick();
	foreach($image_files as $f)
	{
		// Add the commit message.
		$size = getimagesize($f);
		$height = $size[1];
		$im = @imagecreatefromjpeg($f);
		$textcolor = $config['message_text_color'];
		$textcolor = imagecolorallocate($im, $textcolor[0], $textcolor[1], $textcolor[2]);
		$strokecolor = $config['message_stroke_color'];
		$strokecolor = imagecolorallocate($im, $strokecolor[0], $strokecolor[1], $strokecolor[2]);
		$size = $config['message_font_size'];
		$fontfile = $config['message_font_file'];
		$px = $config['message_stroke_size'];
		imagettfstroketext($im, $size, 0, 10, $height-10, $textcolor, $strokecolor, $fontfile, $message, $px);
		imagejpeg($im, $f);
		imagedestroy($im);

		$frame = new Imagick();
		$frame->readImage($f);
		$animation->addImage($frame);
	}
	$path = join(DIRECTORY_SEPARATOR, array($config['image_dir'], $hash.'.gif'));
	$animation->optimizeImageLayers();
	return $animation->writeImages($path, true) ? $path : null;
}

if(!array_key_exists('hash', $_POST)) {
	// We'll be outputting a PDF
	header('Content-Type: text/plain');
	// It will be called downloaded.pdf
	header('Content-Disposition: attachment; filename="lol-post-commit"');
	// Print the content of the post commit hook, as downloaded from GitHub.
	// We could just read it out of the folder on the webserver, but this way
	// we don't need to deploy continously as changes are made to the post commit
	// hook.
	echo file_get_contents($config['post_commit_download_url']);
} else {
	$hash = $_POST['hash'];
	$message = $_POST['message'];
	$author = $_POST['author'];
	$image_files = $_FILES['image']['tmp_name'];
	$gif_filename = process_image_files($hash, $message, $image_files);

	$this_url=trim("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", '/');
	$image_link = "$this_url/$gif_filename";

	slack_post_message($author, $message, $image_link);
}
?>