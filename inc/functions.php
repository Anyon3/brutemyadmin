<?php

    function pma_version($url) {

        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );

            $page = @file_get_contents("$url/doc/html/index.html",false, stream_context_create($arrContextOptions));

            //4.x
            if(!empty($page)) {
                preg_match("/\<a href\=\"#\"\>phpMyAdmin (.*?) documentation\<\/a\>/", $page, $extract);
                $version = $extract[1];
            }

            else {
                // ? < 4.x
                $page = @file_get_contents("$url/Documentation.html", false, stream_context_create($arrContextOptions));

                if(!empty($page)) {
                    preg_match("/\<title\>phpMyAdmin (.*?) Documentation\<\/title\>/", $page, $extract);
                    $version = $extract[1];
                }

                //Probably not a phpMyAdmin root directory
                else {
                   $version = NULL;
                }
            }

            return $version;
    }

?>
