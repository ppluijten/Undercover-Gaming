<?php

require_once "header.php";
$template = new Template("reviews");

$content_html = "";
$reviewArray = $content->GetContentItems(4, 0, 20);
foreach($reviewArray as $reviewItem) {
    $content_html .= "<tr><td style='width: 40px;'>" . $content->GetPlatformTag($reviewItem['tag']) . "</td><td style='width: 480px;'><a href='content.php?id=" . $reviewItem['id'] . "'>" . $reviewItem['title'] . "</a></td><td style='width: 70px;'>" . $reviewItem['comments'] . " reacties</td></tr>";
}

$template->SetVariable("title", "Undercover-Gaming :: Reviews");
$template->SetVariable("reviewitems", $content_html);

$template->Output();

?>