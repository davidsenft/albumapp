<?php

$ua = $_SERVER["HTTP_USER_AGENT"];
$android = strpos($ua, 'Android') ? true : false;
$blackberry = strpos($ua, 'BlackBerry') ? true : false;
$iphone = strpos($ua, 'iPhone') ? true : false;
$palm = strpos($ua, 'Palm') ? true : false;
$firefox = strpos($ua, 'Firefox') ? true : false;
$mobile = $android || $blackberry || $iphone || $palm ? true : false;