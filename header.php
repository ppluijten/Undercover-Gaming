<?php

// Turn off error reporting, except actual errors
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);

// Configuration data
require "config.php";

// Database modification and usage class
require "source/db.class.php";
$db = new DB($db_host, $db_username, $db_password, $db_database);

// Content modification and usage class
require "source/content.class.php";
$content = new Content();

// User modification and usage class
require "source/user.class.php";
$user = new User();

// Settings
require "source/settings.class.php";
$settings = new Settings();

// Templates
require "source/template.class.php";

// HTTP
require "source/http.class.php";

// Pre-variables
$prevars = array();

?>