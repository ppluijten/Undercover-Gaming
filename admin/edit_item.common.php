<?php

require_once ("../xajax/xajax_core/xajax.inc.php");

$xajax = new xajax("edit_item.server.php");
$xajax->configure('javascript URI','../xajax/');
$xajax->register(XAJAX_FUNCTION,"previewitem");
$xajax->register(XAJAX_FUNCTION,"edititem");

?>