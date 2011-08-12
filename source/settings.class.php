<?php

class Settings {

    var $settings = array();

    function GetSetting($name) {
        return (string) $this->settings[$name];
    }

    function SettingExists($name) {
        if (isset($this->settings[$name])) {
            return true;
        } else {
            return false;
        }
    }

    function LoadSettings() {
        $settings = array(
            "content_news_sort"     =>  "c_date DESC",
            "content_reviews_sort"  =>  "c_date DESC",
            "content_previews_sort" =>  "c_date DESC"
        );
        $this->settings = $settings;
    }

    function __construct() {
        $this->LoadSettings();
    }

}

?>