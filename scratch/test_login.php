<?php
// Bypass CI index.php just to simulate
$url = 'http://localhost:8080/login';

// 1. GET request to fetch cookies and captcha
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
$response = curl_exec($ch);
list($header, $body) = explode("\r\n\r\n", $response, 2);

preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $header, $matches);
$cookies = array();
foreach($matches[1] as $item) {
    parse_str($item, $cookie);
    $cookies = array_merge($cookies, $cookie);
}
$cookieStr = '';
foreach ($cookies as $k => $v) {
    $cookieStr .= "$k=$v; ";
}

// Extract captcha question from body
preg_match('/<div class="bg-white rounded px-3 py-2 fw-bold text-dark shadow-sm border text-center" style="font-size: 1.2rem; min-width: 100px;">\s*(.*?)\s*<\/div>/s', $body, $captchaMatches);
$captchaQuestion = trim($captchaMatches[1]);

// Evaluate captcha math (e.g. "5 + 3 = ?")
$parts = explode(' ', $captchaQuestion);
$num1 = (int)$parts[0];
$num2 = (int)$parts[2];
$captchaAnswer = $num1 + $num2;

echo "Solved Captcha: $captchaQuestion -> $captchaAnswer\n";

// 2. POST request to login
$ch2 = curl_init($url);
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch2, CURLOPT_POST, true);
curl_setopt($ch2, CURLOPT_POSTFIELDS, "username=admin&password=admin123&captcha=" . $captchaAnswer);
curl_setopt($ch2, CURLOPT_COOKIE, $cookieStr);
curl_setopt($ch2, CURLOPT_HEADER, true);

$postResponse = curl_exec($ch2);
echo "POST Response Header:\n";
echo $postResponse;
