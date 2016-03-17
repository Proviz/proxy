<?php

$url = "https://www.fl.ru/" . ($_GET['url']);
$userAgent = 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:44.0) Gecko/20100101 Firefox/44.0';

$ch = curl_init($url);

if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);
}

$cookie = array();
foreach ($_COOKIE as $key => $value) {
    $cookie[] = $key . '=' . $value;
}
$cookie = implode('; ', $cookie);

curl_setopt($ch, CURLOPT_COOKIE, $cookie);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);

$res = curl_exec($ch);

list($header, $contents) = preg_split('/([\r\n][\r\n])\\1/', $res, 2);

$status = curl_getinfo($ch);
curl_close($ch);

//echo "<pre>";
//echo htmlentities($res, ENT_SUBSTITUTE | ENT_DISALLOWED);

$header_text = preg_split('/[\r\n]+/', $header);
foreach ($header_text as $header) {
    if (preg_match('/^(?:Content-Type|Content-Language|Set-Cookie):/i', $header)) {
        header($header);
    }
}

print $contents;
