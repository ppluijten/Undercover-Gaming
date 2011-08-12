<?php

require_once "header.php";
$template = new Template("content");

$id = (int) $_GET['id'];

$contentItem = $content->GetContentItem($id);

$content_html = "";
//$content_html .= "<h1>Data</h1>";
//$content_html .= "<pre>" . var_export($contentItem, 1) . "</pre>";
//$content_html .= "<br />";

switch($contentItem['type']) {
    case 1:
        // News
        $content_html .= "<h1>" .  $contentItem['title'] . "</h1>";
        $content_html .= "<h3><i>" . $contentItem['author'] . " (" . date('d-m-Y H:i', $contentItem['date']) . ")</i></h3>";
        $content_html .= $contentItem['text'];
        break;
    case 2:
        // Article
        $object = $content->GetObject($contentItem['objecttype'], $contentItem['object']);

        $content_html .= "<h1>Article: " .  $contentItem['title'] . "</h1>";
        $content_html .= "<h3><i>" . $contentItem['author'] . " (" . date('d-m-Y H:i', $contentItem['date']) . ")</i></h3>";
        $content_html .= "<h4><b>" . $contentItem['description'] . "</b></h4>";
        $content_html .= "<h4><b>Object: " . $object['name'] . "</b></h4>";
        $content_html .= $contentItem['text'];
        break;
    case 3:
        // Preview
        $object = $content->GetObject($contentItem['objecttype'], $contentItem['object']);

        $content_html .= "<h1>Preview: " .  $contentItem['title'] . "</h1>";
        $content_html .= "<h3><i>" . $contentItem['author'] . " (" . date('d-m-Y H:i', $contentItem['date']) . ")</i></h3>";
        $content_html .= "<h4><b>" . $contentItem['description'] . "</b></h4>";
        $content_html .= "<h4><b>Object: " . $object['name'] . "</b></h4>";
        $content_html .= $contentItem['text'];
        break;
    case 4:
        // Review
        $object = $content->GetObject($contentItem['objecttype'], $contentItem['object']);

        $content_html .= "<h1>Preview: " .  $contentItem['title'] . "</h1>";
        $content_html .= "<h3><i>" . $contentItem['author'] . " (" . date('d-m-Y H:i', $contentItem['date']) . ")</i></h3>";
        $content_html .= "<h4><b>" . $contentItem['description'] . "</b></h4>";
        $content_html .= "<h4><b>Object: " . $object['name'] . "</b></h4>";
        $content_html .= $contentItem['text'];
        //TODO: Afmaken
        break;
}

$template->SetVariable("title", "Undercover-Gaming :: Content");
$template->SetVariable("body", $content_html);

$template->Output();

?>