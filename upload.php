<?php

include $_SERVER["DOCUMENT_ROOT"] . DIRECTORY_SEPARATOR . 'wp-load.php';

$ds          = DIRECTORY_SEPARATOR;
$storeFolder = 'cust-uploads';

if (!empty($_FILES)) {

  // Make sure request came from a real user
  if (!get_current_user_id()) {
    print "User ID not available.";
    exit;
  }

  $tempFile = $_FILES['file']['tmp_name'];

  $targetPath = $_SERVER["DOCUMENT_ROOT"] . $ds . $storeFolder . $ds . get_current_user_id() . $ds;
  if (!is_dir($targetPath)) {
    mkdir($targetPath, 077);
  }

  $targetFile =  $targetPath . $_FILES['file']['name'];

  move_uploaded_file($tempFile, $targetFile);
  update_options($targetFile);
}

function update_options($targetFile)
{
  $optionPrefix = "mp-upload-user-";
  $optionName = $optionPrefix . get_current_user_id();

  // delete_option($optionName);

  $option = get_option($optionName);
  if ($option) {
    // Add new image to existing option
    $lastDelim = strrpos($option, '"');
    $replacement =  "\", \"" . $targetFile;
    $option = substr_replace($option, $replacement, $lastDelim, 0);
    update_option($optionName, $option);
  } else {
    // Create new option, populate with new image
    add_option($optionName, '["' . $targetFile . '" ]');
  }
}
