<?php

if(isset($_GET['secure'])) {
    echo "<pre>" . var_export($_COOKIE, 1) . "</pre>";
}

$sessionhash = $_COOKIE['bbsessionhash'];
$userid = $_COOKIE['userid'];
echo md5($sessionhash . '?lz') . "<br />";
echo md5('e7bd361d4233941fede49f0f0e9f4550' . '?lz') . "<br />";
echo md5('2fa7f04d58e84949c349dc5daa70c022' . '?lz');

?>
