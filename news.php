<?php

require_once "header.php";
$template = new Template("news");

$content_html = "";
$newsArray = $content->GetContentItems(1, 0, 20);
foreach($newsArray as $newsItem) {
    $content_html .= "<tr><td style='width: 40px;'>" . $content->GetPlatformTag($newsItem['tag']) . "</td><td style='width: 480px;'><a href='content.php?id=" . $newsItem['id'] . "'>" . $newsItem['title'] . "</a></td><td style='width: 70px;'>" . $newsItem['comments'] . " reacties</td></tr>";
}

$template->SetVariable("title", "Undercover-Gaming :: News");
$template->SetVariable("newsitems", $content_html);

$template->Output();

?>