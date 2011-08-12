<?php

require_once "header.php";

echo "<pre>" . var_export($user->userdata, 1) . "</pre>";
echo "<img src='http://www.undercover-gaming.nl/forum/image.php?u=" . $user->userdata['userid'] . "'>";

?>