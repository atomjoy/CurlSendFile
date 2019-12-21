# CurlSendFile Php class
Send big files with php curl

```php
<?php
// Import class
require('CurlSendFile.php');
try
{
    // Create object
    $curl = new CurlSendFile();
    
    // Send single file
    echo $curl->Send("http://localhost/upload.php", 'img/wolf.jpg', ['id'=>'1234567890']);
    
    // Params
    $timeout = 60; // Timeout default: 60
    $selfsigned = 1; // Allow selfsigned ssl 1 or 0 default: 1
    $attr = 'my_file'; // Files form name $_FILES['my_file'] default: file
    
    // Send with custom field name
    echo $curl->Send("https://localhost/upload.php", 'img/wolf.jpg', ['id'=>'1234567890'], $timeout, $selfsigned, $attr);
}
catch(Exception $e)
{
    echo $e->getMessage();
    echo $e->getCode();
}
?>
```
