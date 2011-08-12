<?php

class Template {

    var $name = "";
    var $content = "";
    var $variables = array();
    var $folder = "";

    function __construct($template_name, $title = "", $folder = "", $noheader = false) {
        $this->name = $template_name;
        $this->folder = $folder;

        $this->SetVariable("folder", $folder);
        $this->SetVariable("title", $title);

        global $prevars;
        if(is_array($prevars['templates'])) {
            foreach($prevars['templates'] as $template_id => $template_content) {
                $this->SetVariable($template_id, $template_content);
            }
        }

        if(!$noheader) { $this->LoadHeader(); }
        $this->LoadTemplate();
        if(!$noheader) { $this->LoadFooter(); }
    }

    private function LoadTemplate() {
        $content = file_get_contents($this->folder . "content/" . $this->name . ".content.html");
        $this->content .= $content;
    }

    function SetVariable($var, $value) {
        $this->variables[$var] = $value;
    }

    private function ReplaceVariables() {
        foreach ($this->variables as $variable => $value) {
            $this->content = str_replace("{" . $variable . "}", "$value", $this->content);
        }
        //TODO: Make a literal option
        $this->content = eregi_replace("\{[^\}]*\}", "", $this->content);
    }

    private function LoadHeader() {
        $header = file_get_contents($this->folder . "content/header.content.html");
        $this->content .= $header;
    }

    private function LoadFooter() {
        $footer = file_get_contents($this->folder . "content/footer.content.html");
        $this->content .= $footer;
    }

    function Output() {
        $this->ReplaceVariables();
        echo $this->content;
    }

    function ReturnOutput() {
        $this->ReplaceVariables();
        return $this->content;
    }

}

?>