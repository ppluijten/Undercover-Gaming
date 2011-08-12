<?php

require_once("header.php");
require("usercontrol.common.php");

// User data
$usertemplate = new Template("usercontrol", "", "", true);
$logintemplate = new Template("login", "", "", true);
if($user->userdata['loggedin']) {
    $usertemplate->setVariable("userdata", "<div style='float: left; width: 95px; height: 95px;'><img style='width: 95px; height; 95px;' src='http://www.undercover-gaming.nl/forum/image.php?u=" . (int) $user->userdata['userid'] . "'></div><div style='float: left; margin-left: 10px;'>Welkom, " . $user->userdata['username'] . "!<br />Beheerderspaneel<br />Vriendenlijst<br />Ga naar mijn profiel</div>");
} else {
    $usertemplate->setVariable("userdata", "<div style='float: left; width: 95px; height: 95px;'><img style='width: 95px; height; 95px;' src='images/avatar.jpg'></div><div style='float: left; margin-left: 10px;'>Welkom, Gast!<br /><a onClick='document.getElementById(\"logindiv\").style.display=\"\";'>Inloggen</a><br />Registreren<br />Wachtwoord vergeten");
    $usertemplate->setVariable("loginform", $logintemplate->ReturnOutput());
}

$prevars['templates']['usercontrol'] = $usertemplate->ReturnOutput();
$prevars['templates']['xajaxJavascript'] = $xajax->printJavascript();

if(stripos($_SERVER['HTTP_REFERER'], "undercover-gaming.nl/forum/login.php")) {
    $prevars['templates']['body_onload'] = "notifyParent();";
}

?>