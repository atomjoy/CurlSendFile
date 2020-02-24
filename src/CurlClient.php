<?php
namespace PhpCurl;
use \Exception;

/**
 * CurlClient
 * Send get, post, json, files request with/without tls/ssl
 */
class CurlClient
{
    protected $Curl;
    protected $Port = 0;
    protected $Url = "localhost";
    protected $Method = "GET";
    protected $Timeout = 60;
    protected $ConnectionTimeout = 10;
    protected $FollowLocation = true;
    protected $VerifySsl = true;
    protected $Json = false;
    protected $Headers = array();
    protected $Files = array();
    protected $Data = array();
    protected $Token = "";
    protected $InputFileName = "files";
    protected $Params = '';
    protected $Gzip = true;
    protected $Http2 = true;
    protected $Session = true;
    protected $ShowHeader = false;
    // Proxy
    protected $ProxyHost = "";
    protected $ProxyPort = "";
    protected $ProxyUser = "";
    protected $ProxyPass = "";

    function __construct(){
        $this->Curl = curl_init();
    }

    function SetDisableSession(){
        $this->Session = false;
    }

    function AddPort($port = 0){
        if($port > 0){
            $this->Port = $port;
        }
    }

    function AddUrl($url = "localhost"){
        $this->Url = $url;
    }

    function AddToken($token = ""){
        $this->Token = $token;
    }

    function AddHeader($str){
        $this->Headers[] = $str;
    }

    function AddData($name, $value){
        if(!empty($name) && strlen($name) > 0){
            $this->Data[$name] = $value;
        }
    }

    function AddFile($path){
        if(file_exists($path)){
            $this->Files[] = $path;
        }
    }

    function SetMethod($name = "GET"){
        if($name == "POST" || $name == "PUT" || $name == "DELETE"){
            $this->Method = $name;
        }else{
            $this->Method = "GET";
        }
    }

    function SetJson(){
        $this->Json = true;
    }

    function SetHttp1(){
        $this->Http2 = false;
    }

    function SetDisableGzip(){
        $this->Gzip = false;
    }

    function SetShowHeader(){
        $this->ShowHeader = true;
    }

    function SetAllowSelfsigned(){
        $this->VerifySsl = false;
    }

    function SetInputFileName($name){
        if(!empty($name) && strlen($name) > 0){
            $this->InputFileName = $name;
        }
    }

    function SetProxy($host = "", $port = "", $user = "", $pass = "")
    {
        $this->ProxyHost = $host;
        $this->ProxyPort = $port;
        $this->ProxyUser = $user;
        $this->ProxyPass = $pass;
    }

    function Send(){
        // Port
        if($this->Port > 0){
            curl_setopt($this->Curl, CURLOPT_PORT, $this->Port);
        }
        // GET url params
        if($this->Method == "GET"){
            foreach($this->Data as $k => $v){
                $this->Params .= $k.'='.$v.'&';
            }
            $this->Params = trim($this->Params, '&');
            curl_setopt($this->Curl, CURLOPT_URL, $this->Url.'?'.$this->Params);
        }else{
            curl_setopt($this->Curl, CURLOPT_URL, $this->Url);
        }
        // Gzip encoding
        if($this->Gzip == true){
            curl_setopt($this->Curl, CURLOPT_ENCODING, 'gzip');
        }
        // Http2
        if($this->Http2 == true){
            curl_setopt($this->Curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        }
        // Show header
        if($this->ShowHeader == true){
            curl_setopt($this->Curl, CURLOPT_HEADER, true);
        }
        curl_setopt($this->Curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->Curl, CURLOPT_CUSTOMREQUEST, $this->Method);
        curl_setopt($this->Curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->Curl, CURLOPT_FOLLOWLOCATION, $this->FollowLocation);
        curl_setopt($this->Curl, CURLOPT_CONNECTTIMEOUT, $this->ConnectionTimeout);
        curl_setopt($this->Curl, CURLOPT_TIMEOUT, $this->Timeout);
        curl_setopt($this->Curl, CURLOPT_SSLVERSION, 6);
        curl_setopt($this->Curl, CURLOPT_SAFE_UPLOAD, false); // !!!! required as of PHP 5.6.0 for files !!!
        curl_setopt($this->Curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-GB; rv:1.9.2) Gecko/20100115 Firefox/3.6 (.NET CLR 3.5.30729)");

        // Proxy
        if (!empty($this->ProxyHost) && !empty($this->ProxyPort)) {
            curl_setopt($this->Curl, CURLOPT_PROXY, $this->ProxyHost.':'.$this->ProxyPort);
            if (!empty($this->ProxyUser) && !empty($this->ProxyPass)) {
                curl_setopt($this->Curl, CURLOPT_PROXYUSERPWD, $this->ProxyUser.':'.$this->ProxyPass);
            }
        }

        // Add files
        if($this->Method == "POST" && $this->Json == false){
            // Add files
            foreach ($this->Files as $k => $v) {
                $f = realpath($v);
                if(file_exists($f)){
                    $fc = new CurlFile($f, mime_content_type($f), basename($f));
                    // For -> $_FILES["files"];
                    $this->Data[$this->InputFileName."[".$k."]"] = $fc;
                }
            }
        }

        // Not GET
        if($this->Method != "GET"){
            curl_setopt($this->Curl, CURLOPT_POST, 1);

            // Json headers

            if($this->Json == true){
                curl_setopt($this->Curl, CURLOPT_POSTFIELDS, json_encode($this->Data));
                $this->Headers[] = 'Content-Type: application/json';
                $this->Headers[] = 'Content-Length: ' . strlen(json_encode($this->Data));
            }else{
                if(count($this->Files) > 0){
                    curl_setopt($this->Curl, CURLOPT_POSTFIELDS, $this->Data);
                }else{
                    curl_setopt($this->Curl, CURLOPT_POSTFIELDS, http_build_query($this->Data));
                    $this->Headers[] = 'Content-Type: application/x-www-form-urlencoded';
                }
            }
        }

        // Set token
        if(!empty($this->Token)){
            $this->Headers[] = 'Authorization: Bearer '.$this->Token;
        }

        // Add headers
        if(!empty($this->Headers)){
            curl_setopt($this->Curl, CURLOPT_HTTPHEADER, $this->Headers);
        }

        // Ssl/Tls
        if($this->VerifySsl == true){
	    curl_setopt($this->Curl, CURLOPT_SSL_VERIFYHOST, 2);
	    curl_setopt($this->Curl, CURLOPT_SSL_VERIFYPEER, 1);
        }else{
            curl_setopt($this->Curl, CURLOPT_SSL_VERIFYHOST, 0);
	    curl_setopt($this->Curl, CURLOPT_SSL_VERIFYPEER, 0);
        }

        // Session
        if($this->Session){
            curl_setopt($this->Curl, CURLOPT_COOKIESESSION, true );
            curl_setopt($this->Curl, CURLOPT_COOKIEJAR, 'cookies.txt');
            curl_setopt($this->Curl, CURLOPT_COOKIEFILE, 'cookies.txt');
        }

        // Execute
        $this->Result = curl_exec($this->Curl);

        // Error code
        $this->StatusCode = curl_getinfo($this->Curl, CURLINFO_HTTP_CODE);

        // Error message
        if (curl_errno($this->Curl)) {
            $this->Error = curl_error($this->Curl);
            throw new Exception('CURL_ERROR '.$this->Error, $this->StatusCode);
        }
        curl_close($this->Curl);

        // Response
        return $this->Result;
    }
}

/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

try{

    $curl = new CurlClient();
    // Host
    $curl->AddUrl("http://domain.xx/curl-test.php");

    // Method POST
    $curl->SetMethod("POST");

    // Data
    $curl->AddData("username","Max");
    $curl->AddData("email","ho@email.xx");

    // Add file
    $curl->AddFile("router.php");

    // Force ssl
    $curl->SetAllowSelfsigned();

    // Send as Json
    // $curl->SetJson();

    // Send
    echo $curl->Send();

}catch(Exception $e){
    print_r($e);
}
*/
?>
