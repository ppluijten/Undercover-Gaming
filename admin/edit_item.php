<?php

require_once("../header.php");
require("edit_item.common.php");
$template = new Template("edit_item", "Undercover-Gaming :: Edit Item", "../");

$id = (int) $_GET['id'];
$contentItem = $content->GetContentItem($id);
$objectid = (int) $contentItem['object'];
$objecttype = (int) $contentItem['objecttype'];

$template->SetVariable("xajaxJavascript", $xajax->printJavascript());
$template->SetVariable("body_onload", "xajax_previewitem($id); set_type($objecttype);");
$template->SetVariable("item_title", $contentItem['title']);
$template->SetVariable("item_description", $contentItem['description']);
$template->SetVariable("text_orig", $contentItem['text_orig']);
$template->SetVariable("active", $contentItem['active']);
$template->SetVariable("objecttype", $objecttype);
$template->SetVariable("checked_0", $objecttype == 0 ? 'checked ' : '');
$template->SetVariable("checked_1", $objecttype == 1 ? 'checked ' : '');
$template->SetVariable("checked_2", $objecttype == 2 ? 'checked ' : '');

$games_html = "";
$games = $content->GetGames();
foreach($games as $gameid => $gamename) {
    $games_html .= "<option id='option_$gameid' value='$gameid'" . ($gameid == $objectid ? " checked='1'" : "") . ">$gamename</option>";
}

$template->SetVariable("games", $games_html);

$companies_html = "";
$companies = $content->GetCompanies();
foreach($companies as $companyid => $companyname) {
    $companies_html .= "<option id='option_$companyid' value='$companyid'" . ($companyid == $objectid ? " checked='1'" : "") . ">$companyname</option>";
}

$template->SetVariable("companies", $companies_html);

if($contentItem['type'] == 4) {
    $review_extra = '<tr><td><b>Conclusion</b>:</td><td><input type="text" name="conclusion" id="conclusion" size="30" value="' . $contentItem['conclusion'] . '" /></td></tr>';
    $review_extra .= '<tr><td><b>Rating</b>:</td><td><input type="text" name="rating" id="rating" size="3" value="' . $contentItem['rating'] . '" /></td></tr>';

    $template->SetVariable("review_extra", $review_extra);
}

$template->Output();

/*TODO: c_sub_type
c_platforms
c_date
c_spotlight = 0,1,2
c_date_online
c_image
c_event
c_editor_id*/

?>