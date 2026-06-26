<?php
$url = 'http://localhost:8080/login';

// 1. Login
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

preg_match('/<div class="bg-white rounded px-3 py-2 fw-bold text-dark shadow-sm border text-center" style="font-size: 1.2rem; min-width: 100px;">\s*(.*?)\s*<\/div>/s', $body, $captchaMatches);
$parts = explode(' ', trim($captchaMatches[1]));
$captchaAnswer = (int)$parts[0] + (int)$parts[2];

$ch2 = curl_init($url);
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch2, CURLOPT_POST, true);
curl_setopt($ch2, CURLOPT_POSTFIELDS, "username=250420501100004&password=admin123&captcha=" . $captchaAnswer);
curl_setopt($ch2, CURLOPT_COOKIE, $cookieStr);
curl_setopt($ch2, CURLOPT_HEADER, true);
$postResponse = curl_exec($ch2);

// Extract new cookies from POST redirect
list($header2, $body2) = explode("\r\n\r\n", $postResponse, 2);
preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $header2, $matches2);
foreach($matches2[1] as $item) {
    parse_str($item, $cookie);
    $cookies = array_merge($cookies, $cookie);
}
$cookieStr2 = '';
foreach ($cookies as $k => $v) {
    $cookieStr2 .= "$k=$v; ";
}

// 2. Access Dashboard
$ch3 = curl_init("http://localhost:8080/dashboard");
curl_setopt($ch3, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch3, CURLOPT_COOKIE, $cookieStr2);
$dashboardResponse = curl_exec($ch3);

if (empty($dashboardResponse)) {
    echo "DASHBOARD IS COMPLETELY EMPTY / FATAL ERROR\n";
    // Check error logs directly
    echo "Checking latest php error log:\n";
    $logFiles = glob(__DIR__ . '/../writable/logs/*.log');
    if ($logFiles) {
        $latest = end($logFiles);
        echo file_get_contents($latest);
    }
} else {
    echo "DASHBOARD LOADED " . strlen($dashboardResponse) . " BYTES\n";
    echo "LAST 500 CHARS:\n";
    echo substr($dashboardResponse, -500);
}
