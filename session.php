<?php

if(isset($_GET['secure'])) {
    echo "<pre>" . var_export($_SESSION, 1) . "</pre>";
}

?>