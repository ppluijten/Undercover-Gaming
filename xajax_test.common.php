<?php

require_once ("xajax/xajax_core/xajax.inc.php");

$xajax = new xajax("xajax_test.server.php");
$xajax->configure('javascript URI','xajax/');
$xajax->registerFunction("");

?>