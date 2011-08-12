<?php

class User {

    private $loggedin = false;
    private $userid = 0;
    var $userdata = array();

    function __construct() {
        $this->CheckUser();
    }
    
    function CheckUser() {
        $this->CheckLogin();
        $this->CheckUserData();
        $this->CheckUserGroup();
        $this->CheckCrewData();
        $this->CheckCrewFunction();
    }

    private function CheckLogin() {
        global $db;

        $getSessionData = "
            SELECT userid, loggedin
            FROM VBsession
            WHERE sessionhash = '" . $db->EscapeString($_COOKIE['bbsessionhash']) . "'
            LIMIT 1";
        $sqlSessionData = $db->GetQuery($getSessionData);
        $sessionData = $db->GetAssoc($sqlSessionData);

        $userid = (int) $sessionData['userid'];
        $loggedin = (int) $sessionData['loggedin'];

        if ($loggedin == 2 && $userid > 0) {
            $this->loggedin = true;
            $this->userid = $userid;
        } else {
            $this->loggedin = false;
            $this->userid = 0;
        }

        $this->userdata = array();
        $this->userdata['loggedin'] = $this->loggedin;
        $this->userdata['userid'] = $this->userid;
    }

    private function CheckUserData() {
        global $db;

        if($this->loggedin) {
            $getUserData = "
                SELECT userid, usergroupid, username, email, usertitle
                FROM VBuser
                WHERE userid = '" . $db->EscapeString($this->userid) . "'
                LIMIT 1";
            $sqlUserData = $db->GetQuery($getUserData);
            $userdata = $db->GetAssoc($sqlUserData);

            $this->userdata['usergroup'] = (int) $userdata['usergroupid'];
            $this->userdata['username'] = (string) $userdata['username'];
            $this->userdata['email'] = (string) $userdata['email'];
            $this->userdata['usertitle'] = (string) $userdata['usertitle'];
        }
    }

    private function CheckUserGroup() {
        global $db;

        if($this->loggedin && (int) $this->userdata['usergroup'] > 0) {
            $getUserGroup = "
                SELECT usergroupid, usertitle
                FROM VBusergroup
                WHERE usergroupid = '" . $db->EscapeString($this->userdata['usergroup']) . "'
                LIMIT 1";
            $sqlUserGroup = $db->GetQuery($getUserGroup);
            $usergroup = $db->GetAssoc($sqlUserGroup);
            $this->userdata['usergroupname'] = $usergroup['usertitle'];
        }
    }

    private function CheckCrewData() {
        global $db;

        if($this->loggedin) {
            $getCrewData = "
                SELECT crew_id, crew_user_id, crew_name, crew_function, crew_text, crew_avatar
                FROM ug_crew
                WHERE crew_user_id = '" . $db->EscapeString($this->userid) . "'
                LIMIT 1";
            $sqlCrewData = $db->GetQuery($getCrewData);
            $crewdata = $db->GetAssoc($sqlCrewData);

            $this->userdata['crewid'] = $crewdata['crew_id'];
            $this->userdata['crewname'] = $crewdata['crew_name'];
            $this->userdata['crewfunction'] = $crewdata['crew_function'];
            $this->userdata['crewtext'] = $crewdata['crew_text'];
            $this->userdata['crewavatar'] = $crewdata['crew_avatar'];
        }
    }

    private function CheckCrewFunction() {
        global $db;

        if($this->loggedin && (int) $this->userdata['usergroup'] > 0) {
            $getUserGroup = "
                SELECT u_id, u_name, u_levels, u_order
                FROM ug_userlevels
                WHERE u_id = '" . $db->EscapeString($this->userdata['crewfunction']) . "'
                LIMIT 1";
            $sqlUserGroup = $db->GetQuery($getUserGroup);
            $usergroup = $db->GetAssoc($sqlUserGroup);
            $this->userdata['crewfunctionname'] = $usergroup['u_name'];
            $this->userdata['crewlevels'] = $usergroup['u_levels'];
            $this->userdata['creworder'] = $usergroup['u_order'];
        }
    }

}

?>