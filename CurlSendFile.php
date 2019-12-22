<?php
/**
 * CurlSendFile class
 */
class CurlSendFile
{
    /**
     * Send function
     * Send files and post data from php with curl
     *
     * @param string $url Upload url
     * @param string $f File path
     * @param array $data Post form data array
     * @param integer $timeout Timeout 60s
     * @param integer $selfsigned Allow self signed ssl: 1 or 0
     * @param string $form_attr Files array $_FILES['file'] field name: file
     * @return string Json string array
     */
    function Send($url, $f, $data = [], $timeout = 60, $selfsigned = 1, $form_attr = 'file', $token = ""){
        $f = realpath($f);
        // $_POST
        foreach ($data as $k => $v) { $post[$k] = $v; }
        // $_FILES['file']
        $post[$form_attr] = new CurlFile($f, mime_content_type($f), basename($f));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post); // large json or array
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, (int) $timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, (int) $timeout);
        curl_setopt($ch, CURLOPT_SAFE_UPLOAD, 1);
        curl_setopt($ch, CURLOPT_POSTREDIR, 3);
        if($selfsigned){
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSLVERSION, 6);
        }
        if(!empty($token)){
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer ".$token));
        }
        $res = curl_exec($ch);
        if(curl_errno($ch)){
            // Show error
            throw new Exception(curl_error($ch), curl_errno($ch));
        }
        return $res;
    }
}

/*
try
{
    $curl = new CurlSendFile();
    echo $curl->Send("https://localhost:4443/upload.php", 'img/wolf.jpg', ['id'=>'1234567890']);
    echo $curl->Send("https://localhost:4443/upload.php", 'img/firefox.tar.gz', ['id'=>'1234567890']);
}
catch(Exception $e)
{
    echo $e->getMessage();
    echo $e->getCode();
}
*/
?>
