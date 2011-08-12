<?php

require("http_class.class.php");

class HTTP {
    var $hostname = "";
    var $request = "";
    var $request_headers = array();
    var $request_body = "";
    var $reply_headers = array();
    var $reply_body = "";
    var $error = "";

    function __construct($url, $method = "POST", $postvalues = array()) {
        set_time_limit(0);
        $http = new http_class;
        $http->timeout = 0;
        $http->data_timeout = 0;
        $http->debug = 0;
        $http->html_debug = 1;

        $error = $http->GetRequestArguments($url, $arguments);

        $arguments["RequestMethod"] = $method;
        $arguments["PostValues"] = $postvalues;
        $arguments["Referer"] = "http://revamp.undercover-gaming.nl";

        $this->hostname = (string) $arguments["HostName"];
        $error = $http->Open($arguments);

        if($error == "")
        {
            $error = $http->SendRequest($arguments);
            if($error == "")
            {
                $this->request = (string) $http->request;
                for(Reset($http->request_headers), $header=0; $header<count($http->request_headers); Next($http->request_headers), $header++)
                {
                    $header_name = Key($http->request_headers);
                    if(GetType($http->request_headers[$header_name]) == "array")
                    {
                        $this->request_headers[$header_name] = array();
                        for($header_value=0; $header_value < count($http->request_headers[$header_name]); $header_value++)
                            $this->request_headers[$header_name][$header_value] = (string) $http->request_headers[$header_name][$header_value];
                    }
                    else
                        $this->request_headers[$header_name] = (string) $http->request_headers[$header_name];
                }
                $this->request_body = (string) $http->request_body;

                $headers = array();
                $error = $http->ReadReplyHeaders($headers);
                if($error == "")
                {
                    for(Reset($headers), $header=0; $header < count($headers); Next($headers), $header++)
                    {
                        $header_name = Key($headers);
                        if(GetType($headers[$header_name]) == "array")
                        {
                            $this->reply_headers[$header_name] = array();
                            for($header_value=0; $header_value < count($headers[$header_name]); $header_value++)
                                $this->reply_headers[$header_name][$header_value] = (string) $headers[$header_name][$header_value];
                        }
                        else
                            $this->reply_headers[$header_name] = (string) $headers[$header_name];
                    }

                    for(;;)
                    {
                        $error = $http->ReadReplyBody($body, 1000);
                        if($error != "" || strlen($body) == 0)
                            break;
                        $this->reply_body .= (string) $body;
                    }
                }
            }
            $http->Close();
        }
        if(strlen($error))
            $this->error = (string) $error;
    }
}

?>