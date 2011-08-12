<?php

require_once("../header.php");
require("usercontrol.common.php");

function loginform($formvalues)
{
    $objResponse = new xajaxResponse();
    $http = new HTTP("http://www.undercover-gaming.nl/getpost.php", "POST", $formvalues);
    return $objResponse;
}

$xajax->processRequest();

?>