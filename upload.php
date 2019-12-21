<?php
echo "<pre>";

// Show post array
print_r($_POST);

// Show files array
print_r($_FILES);

// Allow files with extension
$allow = ["jpg", "png", "gif", "zip", "pdf"];

// Upload
if(!empty($_FILES["file"]["tmp_name"]))
{
  // File extension
  $ext = strtolower(pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION));
  
  // Test extension
  if(in_array($ext, $allow))
  { 
    // Upload dir
    $dir = "media/files";
    
    // Create dir
    mkdir($dir, 0777, true);
    chmod($dir,0777);
    
    // New file name
    $path = $dir . "/" . md5(microtime().uniqid()) . "." . $ext; 
    
    // Save file
    $ok = move_uploaded_file($_FILES["file"]["tmp_name"], $path);

  }else{
    echo "Error extension";
  }
  
}else{
  echo "Empty file";
}
?>
