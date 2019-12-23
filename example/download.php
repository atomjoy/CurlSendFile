<?php
// File
$f = $_GET['file'];

// Speed 1MB/s
$speed = 1024 * 1024 * 1;

// Timeout
set_time_limit(0);

// Clean
@ob_end_clean();

// If file exists (validate file here)
if(file_exists($f)){
    // Header
    header("Content-Description: File Transfer");
    header("Content-Type: application/octet-stream");
    header("Content-Disposition: attachment; filename=\"". basename($f) ."\"");
    header("Content-Transfer-Encoding: binary");
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($f));
    // Download
    $h = fopen($f, 'rb');
    while (!feof($h)) {
        echo fread($h, $speed);
        ob_flush();
        // Download speed
        sleep(1);
    }
    exit;
}else{
    // Show error file
    $f = "img/error-file.png";
    header('Content-Type: ' . mime_content_type($f));
    header("Content-Length: " . filesize($f));
    readfile($f);
}
?>
