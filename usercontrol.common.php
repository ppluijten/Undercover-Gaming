<?php

require_once ("xajax/xajax_core/xajax.inc.php");

$xajax = new xajax("usercontrol.server.php");
$xajax->configure('javascript URI','xajax/');
$xajax->register(XAJAX_FUNCTION,"loginform");

?>