# CurlSendFile Php class
Send big files with php curl

### Set php.ini
```bash
# In php.ini only
file_uploads = On

# In php.ini or in php.user.ini in local directory
max_execution_time = 40000
max_input_time = 40000
max_input_vars = 100
upload_max_filesize = 900M
post_max_size = 900M
```
### Send file with curl
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
