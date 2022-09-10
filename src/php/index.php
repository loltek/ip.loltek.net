<?php

declare(strict_types=1);
function getRemoteIp(): string
{
    // first check cloudflare ip
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        return $_SERVER['HTTP_CF_CONNECTING_IP'];
    }
    // then check Fastly IP
    if (!empty($_SERVER['HTTP_FASTLY_CLIENT_IP'])) {
        return $_SERVER['HTTP_FASTLY_CLIENT_IP'];
    }
    // netiher CF nor Fastly..
    if (!empty($_SERVER['REMOTE_ADDR'])) {
        return $_SERVER['REMOTE_ADDR'];
    }
    throw new \LogicException("unable to find remote ip!");
}
function defaultHeaders()
{
    http_response_code(200);
    header("Content-Type: text/plain; charset=utf-8");
    // header("Content-Encoding: identity");
    header("server: "); // << doesn't work
    header("Cache-Control: no-cache");
    //header("expires:");
}
defaultHeaders();
$ip = getRemoteIp();
$host = (string)($_SERVER['HTTP_HOST'] ?? "");
if (0 === stripos($host, 'www.')) {
    $host = substr($host, strlen('www.'));
}
$host = explode(".", $host, 2)[0];
$str = $ip;
if ($host === "ipn") {
    $str .= "\n";
}
header("Content-Length: " . strlen($str));
echo $str;
