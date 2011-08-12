<?php

require_once "header.php";
require_once "usercontrol.php";
$template = new Template("index", "Undercover-Gaming :: Frontpage");

$content_html = "";
$newsArray = $content->GetContentItems(1, 0, 10);
foreach($newsArray as $newsItem) {
    //TODO: Type opvragen en invullen
    $content_html .= "<div class='entryline'>
        <div class='datum'>" . date('d-m', $newsItem['date']) . "</div>
        <div class='platform'>" . $content->GetPlatformTag($newsItem['tag']) . "</div>
        <div class='type'>Nieuws</div>
        <div class='title'><a href='content.php?id=" . $newsItem['id'] . "'>" . $newsItem['title'] . "</a></div>
        <div class='reactie'>(" . $newsItem['comments'] . ")</div>
    </div>";
}

//TODO: De covered items ophalen en de nieuws items filteren
$template->SetVariable("newsitems", $content_html);
$template->SetVariable("covereditems", $content_html);

$template->Output();

?>