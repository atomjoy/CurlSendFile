<?php
// CORS policy, allow js connections from different hosts
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS');
header('Access-Control-Max-Age: 1728000');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization, X-Auth-Token , X-Requested-With');
?>
