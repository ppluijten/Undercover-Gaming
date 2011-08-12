<?php

require_once "header.php";
$template = new Template("previews");

$content_html = "";
$previewArray = $content->GetContentItems(3, 0, 20);
foreach($previewArray as $previewItem) {
    $content_html .= "<tr><td style='width: 40px;'>" . $content->GetPlatformTag($previewItem['tag']) . "</td><td style='width: 480px;'><a href='content.php?id=" . $previewItem['id'] . "'>" . $previewItem['title'] . "</a></td><td style='width: 70px;'>" . $previewItem['comments'] . " reacties</td></tr>";
}

$template->SetVariable("title", "Undercover-Gaming :: Previews");
$template->SetVariable("previewitems", $content_html);

$template->Output();

?>