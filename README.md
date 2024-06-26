# CurlSendFile Php class
Send big files with php curl

### Set php.ini
```bash
# In php.ini only
file_uploads = On
allow_url_fopen = Off
allow_url_include = Off

# In php.ini or in .user.ini in local directory
max_execution_time = 40000

max_input_time = 40000
max_input_vars = 100

upload_max_filesize = 900M
post_max_size = 900M
```
### Send file with curl
```php
<?php
// Limits
set_time_limit(0);
ini_set('memory_limit', -1);

// Errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

// Import class with composer
require('vendor/autoload.php');
use PhpCurl\CurlSendFile;
use \Exception;

// Start session if needed
// session_start();

try
{
    // Create object
    $curl = new CurlSendFile();
    
    // Send single file and post array
    echo $curl->Send("http://localhost/example/upload.php", 'example/img/wolf.jpg', ['my_post_id' => '1234567890', 'name' => 'Curl upload']);
    
    // Params
    $timeout = 60; // Timeout default: 60
    $selfsigned = 1; // Allow selfsigned ssl 1 or 0 default: 1
    $attr = 'my_file'; // Files form name $_FILES['my_file'] default: file
    
    // Send with custom field name
    echo $curl->Send("https://localhost/example/upload.php", 'example/img/wolf.jpg', ['id'=>'1234567890'], $timeout, $selfsigned, $attr);
}
catch(Exception $e)
{
    echo $e->getMessage();
    echo $e->getCode();
}
?>
```

### Composer require
Install from command line (not active)
```bash
# Add
composer require atomjoy/curlsendfile

# Or
composer require atomjoy/curlsendfile:1.0
composer require atomjoy/curlsendfile:stable

# Install, update
composer update

# If you need to update the autoloader
composer dump-autoload -o
```

Install library from composer.json
```json
{
    "name": "acme/blog",
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/atomjoy/curlsendfile"
        }
    ],
    "require": {
        "atomjoy/curlsendfile": "~1.0"
    }
}
```
