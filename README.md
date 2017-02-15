BruteMyAdmin
------------

Simple PhpMyAdmin bruteforcer

Requirement
------------
php cli (php7 or newer recommanded)
php curl

How to
------------

This script is made to be use in CLI 

Make sure the script have enough privilege to read / write the file cookie.txt

- Add the target PhpMyAdmin root directory to the file inc/target.txt - 1 by line

- Add the list of password to try to the file inc/password.txt - 1 by line

- Add the list of username to try to the file inc/username.txt - 1 by line

Extra
------------

uncomment the line 46, 47 and 68, 69 in the file bma.php for make it use trough TOR

Do no hesitate to report bug, I accept pull request

Version
------------
BruteMyAdmin 0.1 Alpha 
