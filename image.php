<?php
// Show image from hidden location
if(!empty($_GET['file']) && file_exists($_GET['file'])){
    // mime
    $mime = mime_content_type($_GET['file']);
    // if image
    if(strpos($mime,"image/") >= 0){
        header('Content-Type: '.$mime);
        header("Content-Length: " . filesize($_GET['file']));
        readfile($_GET['file']);
        exit;
    }
}
// Error file
$f = "img/error-file.png";
header('Content-Type: ' . mime_content_type($f));
header("Content-Length: " . filesize($f));
readfile($f);
?>
