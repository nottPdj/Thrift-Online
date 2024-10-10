<?php

function saveImage(array $image) : string {
    $tempFileName = $image['tmp_name'];

    $original = @imagecreatefromjpeg($tempFileName);
    if (!$original) $original = @imagecreatefrompng($tempFileName);

    if (!$original) 
    	die(header('Location: ' . $_SERVER['HTTP_REFERER'] . '?error=UnknownImageFormat'));

    $enc_name = md5($image['name'] . $_SESSION['id']) . '.jpg';

    $originalFileName = "../uploads/original_$enc_name";
    $smallFileName = "../uploads/small_$enc_name";
    $mediumFileName = "../uploads/medium_$enc_name";

	$width = imagesx($original);
	$height = imagesy($original);
	$square = min($width, $height);

	imagejpeg($original, $originalFileName);

	$small = imagecreatetruecolor(200, 200);
	imagecopyresized($small, $original, 0, 0, ($width>$square)?($width-$square)/2:0, ($height>$square)?($height-$square)/2:0, 200, 200, $square, $square);
	imagejpeg($small, $smallFileName);

	$medium = imagecreatetruecolor(600, 600);
	imagecopyresized($medium, $original, 0, 0, ($width>$square)?($width-$square)/2:0, ($height>$square)?($height-$square)/2:0, 600, 600, $square, $square);
	imagejpeg($medium, $mediumFileName);

    return $enc_name;

  }

?>