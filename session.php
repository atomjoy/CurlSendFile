<?php
// Secure session
session_set_cookie_params(0, '/', '.' . $_SERVER['SERVER_NAME'], isset($_SERVER['HTTPS']), true);
// Start session
session_start();
?>
