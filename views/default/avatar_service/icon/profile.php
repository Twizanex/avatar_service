<?php

$user = elgg_extract('user', $vars);
if (!($user instanceof ElggUser)) {
	return;
}

if (!$user->hasIcon('master')) {
	return;
}

$icon = $user->getIcon('master');

$image_data = $icon->grabFile();
if (empty($image_data)) {
	return;
}

$x1 = $user->x1;
$x2 = $user->x2;
$y1 = $user->y1;
$y2 = $user->y2;

if (($x1 === null) || ($x1 === $x2) || ($y1 === $y2)) {
	echo $image_data;
	return;
}

// create tempfile for user cropping
$temp_filename = tempnam(elgg_get_data_path(), 'avatar_service') . '.jpg';
$params = [
	'w' => 2048,
	'h' => 2048,
	'x1' => $x1,
	'y1' => $y1,
	'x2' => $x2,
	'y2' => $y2,
	'square' => true,
	'upscale' => false,
];

// apply user cropping config
if (elgg_save_resized_image($icon->getFilenameOnFilestore(), $temp_filename, $params)) {
	echo file_get_contents($temp_filename);
}

// remove temp file
unlink($temp_filename);
