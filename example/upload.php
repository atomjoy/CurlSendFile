<?php
// include('show-errors.php');
// include('js-cors.php');

// Show post array
// print_r($_POST);

// Show files array
// print_r($_FILES);

// Allow files with extension
$allow = ["jpg", "png", "gif", "zip", "pdf", "gz"];

// Max file size (bytes)
$maxsize = 1024 * 1024 * 100; // 100 MB

// Files chmod 0777, 0775, 0755
$chmod = 0777;

// Upload
if(!empty($_FILES["file"]["tmp_name"]))
{
  // Error file size (bytes)
  if($_FILES["file"]['size'] > $maxsize){
    echo "Error size";
    exit;
  }

  // File extension
  $ext = strtolower(pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION));

  // Test extension
  if(in_array($ext, $allow))
  {
    // Upload dir
    $dir = "media/files";

    // Create dir
    mkdir($dir, $chmod, true);
    chmod($dir, $chmod);

    // New file name path
    $path = $dir . "/" . md5(microtime().uniqid()) . "." . $ext;

    // Save file
    $ok = move_uploaded_file($_FILES["file"]["tmp_name"], $path);

    if($ok){
        chmod($path, $chmod);
        echo $path;
    }else{
        echo "Upload file error";
    }

  }else{
    echo "Error extension";
  }

}else{
  echo "Empty file";
}
?>
