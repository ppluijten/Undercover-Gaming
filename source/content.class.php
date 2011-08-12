<?php

class Content {

    function GetContentItems($type, $pageNumber = 0, $pageLimit = 0, $detailed = FALSE) {
        global $db;
        global $settings;

        $itemArray = array();
        $itemOffset = (int) (($pageNumber - 1) * $pageLimit);
        $limit = ((int) $pageLimit != 0) ? (((int) $pageNumber > 0) ? "LIMIT " . (int) $itemOffset . ", " . (int) $pageLimit : "LIMIT " . (int) $pageLimit) : '';

        $order = "";
        switch($type) {
            case 1:
                // News
                if($settings->SettingExists("content_news_sort")) {
                    $order = "ORDER BY " . (string) $settings->GetSetting("content_news_sort");
                }
                break;
            case 3:
                // Preview
                if($settings->SettingExists("content_previews_sort")) {
                    $order = "ORDER BY " . (string) $settings->GetSetting("content_previews_sort");
                }
                break;
            case 4:
                // Review
                if($settings->SettingExists("content_reviews_sort")) {
                    $order = "ORDER BY " . (string) $settings->GetSetting("content_reviews_sort");
                }
                break;
        }

        $getitem = "
            SELECT  *
            FROM    ug_content
            WHERE   c_type = '" . (int) $db->EscapeString($type) . "'
            AND     c_active = '1'
            AND     DATE_FORMAT(c_date_online, '%d-%m-%Y %H:%i') <= '" . date('d-m-Y H:i') . "'
            " . $db->EscapeString($order) . "
            " . $db->EscapeString($limit);
        $sqlitem = $db->GetQuery($getitem);

        /*$getitem = 	"
            SELECT 	*
            FROM 	".TABLE_CONTENT."
            WHERE 	c_type 			=	'".item."'
            AND 	c_active 		= 	'".TRUE."'
            AND 	c_date_online	<= 	'".$dateTime."'
            ORDER BY ".$config->item['orderBy']."
            ".$config->item['orderByType']."
            ".$limit;*/

        while($item = $db->GetArray($sqlitem)) {

                $getComments = "
                    SELECT 	*
                    FROM 	ug_comments
                    WHERE 	comments_cid = '" . (int) $item['c_id'] . "'";
                $sqlComments = $db->GetQuery($getComments);
                $itemComments = $db->GetNumRows($sqlComments);

                /*$itemDateTime = explode(' ', $item['c_date']);
                $itemDateTime2 = explode('-', $itemDateTime[0]);
                $itemDate = $itemDateTime2[2].'/'.$itemDateTime2[1];*/

                $itemDate = strtotime($item['c_date']);

                /*$todayDateTime = explode(' ', $dateTime);
                $todayDateTime2 = explode('-', $todayDateTime[0]);
                if ($itemDateTime2 == $todayDateTime2)
                {
                        $itemTime = explode(':', $itemDateTime[1]);
                        $itemDate = $itemTime[0].':'.$itemTime[1];
                }*/

                //$itemTag = $ug->GetPlatformTag($item['c_platforms']);
                $itemTag = $item['c_platforms'];

                if($detailed)
                {
                        $getAuthor	= "
                            SELECT 	*
                            FROM	ug_crew
                            WHERE	crew_id = '" . (int) $item['c_author_id'] . "'
                            LIMIT	1";
                        $sqlAuthor = $db->GetQuery($getAuthor);

                        $author = $db->GetArray($sqlAuthor);
                        $itemAuthor = $author['crew_name'];

                        $itemText = $this->ConvertBB($item['c_text']);
                }

                $itemEntry = array(
                    'id'        => $item['c_id'],
                    'title'     => stripslashes($item['c_title']),
                    'tag'       => $itemTag,
                    'date'      => $itemDate,
                    'comments'  => $itemComments,
                    'author'    => ($detailed) ? $itemAuthor : '',
                    'text'      => ($detailed) ? $itemText : ''
                );

                array_push($itemArray, $itemEntry);

        }

        return $itemArray;
    }

    function GetContentItem($id) {
        global $db;
        
        if((int) $id <= 0) { return false; }

        // Get the content data
        $getContentItem = "
            SELECT  *
            FROM    ug_content
            WHERE   c_id = '" . (int) $db->EscapeString($id) . "'
            LIMIT   1";
        $sqlContentItem = $db->GetQuery($getContentItem);
        $content = $db->GetArray($sqlContentItem);

        // Get the author
        $getAuthor = "
            SELECT 	crew_name
            FROM	ug_crew
            WHERE	crew_id = '" . (int) $content['c_author_id'] . "'
            LIMIT	1";
        $sqlAuthor = $db->GetQuery($getAuthor);
        $author = $db->GetArray($sqlAuthor);
        $contentAuthor = $author['crew_name'];
        
        // Get the comments
        $getComments = "
            SELECT 	comments_id
            FROM 	ug_comments
            WHERE 	comments_cid = '" . (int) $news['c_id'] . "'";
        $sqlComments = $db->GetQuery($getComments);
        $contentComments = $db->GetNumRows($sqlComments);

        // Get the remaining data
        $contentDate = strtotime($content['c_date']);
        $contentTag = $this->GetPlatformTag($content['c_platforms']);
        $contentTags = $this->GetPlatformTags(explode('|', $content['c_platforms']));
        $contentText = $this->ConvertBB(trim($content['c_text']));

        // Return the data
        $newsEntry = array(
            'id'            => (int) $content['c_id'],
            'type'          => (int) $content['c_type'],
            'subtype'       => (int) $content['c_sub_type'],
            'active'        => (int) $content['c_active'],
            'title'         => (string) stripslashes(trim($content['c_title'])),
            'description'   => (string) stripslashes(trim($content['c_description'])),
            'conclusion'    => (string) stripslashes(trim($content['c_review_conclusion'])),
            'tag'           => (string) $contentTag,
            'tags'          => (string) $contentTags,
            'author'        => (string) $contentAuthor,
            'date'          => (int) $contentDate,
            'comments'      => (int) $contentComments,
            'rating'        => (int) $content['c_review_rating'],
            'object'        => (int) $content['c_obj_id'],
            'objecttype'    => (int) $content['c_obj_type'],
            'text'          => (string) $contentText,
            'text_orig'     => (string) stripslashes(trim($content['c_text']))
        );
        
        return $newsEntry;
    }

    function EditContentItem($id, $data) {
        global $db;

        $id = (int) $id;
        $title = (string) $data['title'];
        $text = (string) $data['text'];

        // Get the content data
        $editContentItem = "
            UPDATE  ug_content
            SET     c_title = '" . (string) $db->EscapeString($title) . "',
                    c_text = '" . (string) $db->EscapeString($text) . "'
            WHERE   c_id = '" . (int) $db->EscapeString($id) . "'
            LIMIT   1";
        $sqlContentItem = $db->GetQuery($editContentItem);
        if(!$sqlContentItem) { return FALSE; }

        return TRUE;
    }

    /*function GetItemType($id) {
        global $db;
        
        $getItemType = "
            SELECT  c_type
            FROM    ug_content
            WHERE   c_id = '" . (int) $db->EscapeString($id) . "'
            LIMIT   1";
        $sqlItemType = $db->GetQuery($getItemType);

        $typeArray = $db->GetArray($sqlItemType);
        $type = (int) $typeArray['c_type'];

        return $type;
    }*/

    function ConvertBB($bericht) {
        /* Init */
        $bericht = stripslashes($bericht);
        $bericht = nl2br($bericht);

        /* Images */
        $bericht = str_replace("[img]","<img src=\"http://",$bericht);
        $bericht = str_replace("[/img]","\">",$bericht);

        /* Links */
        $bericht = eregi_replace("\[url\]www.([^\[]*)","<a href=\"http://www.\\1\" target=_blank>www.\\1", $bericht);
        $bericht = eregi_replace("\[url\]([^\[]*)","<a href=\"\\1\" target=_blank>\\1", $bericht);
        $bericht = eregi_replace("(\[url=)([A-Za-z0-9_~&=;\?:%@#./\-]+[A-Za-z0-9/])(\])", "<a href=\"\\2\" target=_blank>", $bericht);
        $bericht = eregi_replace("(\[/url\])", "</a>", $bericht);
        $bericht = str_replace("http://http://", "http://", $bericht);

        /* Bold, Italic, Underlined */
        $bericht = str_replace("[b]","<b>",$bericht);
        $bericht = str_replace("[/b]","</b>",$bericht);
        $bericht = str_replace("[i]","<i>",$bericht);
        $bericht = str_replace("[/i]","</i>",$bericht);
        $bericht = str_replace("[u]","<u>",$bericht);
        $bericht = str_replace("[/u]","</u>",$bericht);

        /* Headings */
        $bericht = str_replace("[h1]","<h1>",$bericht);
        $bericht = str_replace("[/h1]","</h1>",$bericht);
        $bericht = str_replace("[h2]","<h2>",$bericht);
        $bericht = str_replace("[/h2]","</h2>",$bericht);
        $bericht = str_replace("[h3]","<h3>",$bericht);
        $bericht = str_replace("[/h3]","</h3>",$bericht);

        /* Font */
        $bericht = eregi_replace("(\[color=)([A-Za-z0-9#]*)(\])", "<font color=\"\\2\">", $bericht);
        $bericht = eregi_replace("(\[color=)([\"])([A-Za-z0-9#]*)([\"])(\])", "<font color=\"\\3\">", $bericht);
        $bericht = eregi_replace("(\[/color\])", "</font>", $bericht);
        $bericht = eregi_replace("(\[size=)([0-9]*)(\])", "<font size=\"\\2\">", $bericht);
        $bericht = eregi_replace("(\[size=)([\"])([0-9]*)([\"])(\])", "<font size=\"\\3\">", $bericht);
        $bericht = eregi_replace("(\[/size\])", "</font>", $bericht);

        /* Aligning */
        $bericht = str_replace("[center]","<div align=\"center\">",$bericht);
        $bericht = str_replace("[/center]","</div>",$bericht);
        $bericht = str_replace("[left]","<div align=\"left\">",$bericht);
        $bericht = str_replace("[/left]","</div>",$bericht);
        $bericht = str_replace("[right]","<div align=\"right\">",$bericht);
        $bericht = str_replace("[/right]","</div>",$bericht);

        /* Screenshots */
        $bericht = eregi_replace("(\[screen\])([0-9]*)(\[/screen\])", "<a href=\"http://www.undercover-gaming.nl/media.php?p=screenshot&id=\\2\"><img style=\"border: solid #222222 1px; width: 610px; margin: 3px 3px 3px 3px;\" src=\"http://www.undercover-gaming.nl/screenshot.php?id=\\2\" alt=\"\" /></a>", $bericht);
        $bericht = eregi_replace("(\[screen split=3\])([0-9]*)(\[/screen\])", "<a href=\"http://www.undercover-gaming.nl/media.php?p=screenshot&id=\\2\"><img style=\"border: solid #222222 1px; width: 198px; height: 149px; margin: 3px 3px 3px 3px; float: left;\" src=\"http://www.undercover-gaming.nl/screenshot.php?id=\\2\" alt=\"\" /></a>", $bericht);
        $bericht = eregi_replace("(\[screen split=2\])([0-9]*)(\[/screen\])", "<a href=\"http://www.undercover-gaming.nl/media.php?p=screenshot&id=\\2\"><img style=\"border: solid #222222 1px; width: 301px; height: 226px; margin: 3px 3px 3px 3px; float: left;\" src=\"http://www.undercover-gaming.nl/screenshot.php?id=\\2\" alt=\"\" /></a>", $bericht);

        /* Quotes */
        $bericht = str_replace("[quote]","<div style=\"width: 90%; margin-left: 5%; font-size: 8pt;\">Quote:<hr color=\"#999999\">",$bericht);
        $bericht = str_replace("[/quote]","<hr color=\"#999999\"></div>",$bericht);

        /* Code */
        $bericht = str_replace("[code]","<table border=\"0\" width=\"90%\"><tr><td width=\"10\">&nbsp;</td><td><font size=\"1\">Code:</font></td></tr><tr><td width=\"10\">&nbsp;</td><td><hr color=\"#999999\">",$bericht);
        $bericht = str_replace("[/code]","<hr color=\"#999999\"></td></tr></table>",$bericht);

        /* List */
        //$bericht = eregi_replace("(\[list=)([A-Za-z0-9])(\])(.*)((\[/list\])", "<ol type=\"\\2\">\\4</ol>", $bericht);
        //$bericht = eregi_replace("(\[list=)([\"])([A-Za-z0-9])([\"])(\])", "<ol type=\"\\3\">", $bericht);
        //$bericht = eregi_replace("(\[/list\])", "</ol>", $bericht);
        //$bericht = eregi_replace("(\[list\])(.*)(\[/list\])", "<ul>lijst</ul>", $bericht);
        //$bericht = eregi_replace("(\[/list\])", "</ul>", $bericht);
        //$bericht = eregi_replace("(\[\*\])([A-Za-z0-9]*)(<br />)", "<li>\\2</li>", $bericht);

        return $bericht;
    }

    function GetPlatformTags($platforms) {
        global $db;

        $platformTag = "";
        foreach($platforms as $platformId) {
            $getPlatform = "
                SELECT 	*
                FROM	ug_platforms
                WHERE	p_id = '" . (int) $db->EscapeString($platformId) . "'
                LIMIT 1";
            $sqlPlatform = $db->GetQuery($getPlatform);
            $rowPlatform = $db->GetArray($sqlPlatform);
            $platformTag .= '<div class="category category_' . strtolower($rowPlatform['p_short']) . '"></div>';
        }

        if($platformTag == "")
        {
            $platformTag = '<div class="category category_main"></div>';
        }

        return $platformTag;
    }

    function GetPlatformTag($platforms) {
        global $db;

        if(in_array('main', explode('|', $platforms)))
        {
            $platformTag = "main";
        }
        elseif(in_array('multi', explode('|', $platforms)))
        {
            $platformTag = "multi";
        }
        else
        {
            $platforms = "(" . str_replace("|", ",", $platforms) . ")";
            $getPlatformTags = "SELECT * FROM ug_platforms WHERE p_id IN " . $db->EscapeString($platforms);
            $sqlPlatformTags = $db->GetQuery($getPlatformTags);

            switch($db->GetNumRows($sqlPlatformTags))
            {
                case 0:
                    $platformTag = "main";
                    break;
                case 1:
                    $platformTags = $db->GetArray($sqlPlatformTags);
                    $platformTag = strtolower($platformTags['p_short']);
                    break;
                default:
                    $platformTag = "multi";
                    break;
            }
        }
        $platformTag = '<div class="category category_' . $platformTag . '"></div>';
        return $platformTag;
    }

    function GetObject($type, $id) {
        switch($type) {
            case 1:
                // Game
                $object = $this->GetGame($id);
                break;
            case 2:
                // Company
                $object = $this->GetCompany($id);
                break;
            default:
                // None
                $object = array();
                break;
        }

        return $object;
    }

    function GetGames() {
        global $db;
        $games = array();

        $getGames = "SELECT g_id, g_title FROM ug_games";
        $sqlGames = $db->GetQuery($getGames);
        while($game = $db->GetArray($sqlGames)) {
            $games[(int) $game['g_id']] = (string) $game['g_title'];
        }

        return $games;
    }

    function GetCompanies() {
        global $db;
        $companies = array();

        $getCompanies = "SELECT c_id, c_name FROM ug_companies";
        $sqlCompanies = $db->GetQuery($getCompanies);
        while($company = $db->GetArray($sqlCompanies)) {
            $companies[(int) $company['c_id']] = (string) $company['c_name'];
        }

        return $companies;
    }

    function GetGame($id) {
        global $db;
        
        $getGame = "SELECT g_title FROM ug_games WHERE g_id = '" . (int) $db->EscapeString($id) . "'";
        $sqlGame = $db->GetQuery($getGame);
        $game = $db->GetArray($sqlGame);
        
        return array('name' => (string) stripslashes(trim($game['g_title'])));
    }

    function GetCompany($id) {
        global $db;

        $getCompany = "SELECT c_name FROM ug_companies WHERE c_id = '" . (int) $db->EscapeString($id) . "'";
        $sqlCompany = $db->GetQuery($getCompany);
        $company = $db->GetArray($sqlCompany);

        return array('name' => (string) stripslashes(trim($company['c_name'])));
    }

}

?>