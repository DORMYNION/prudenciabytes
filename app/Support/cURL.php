<?php

namespace FI\Support;

class cURL
{
    public static function post($url, $postVars)
    {
        $urlString = '';

        foreach ($postVars as $key => $var) {
            $postVars[$key] = urlencode($var);
        }

        foreach ($postVars as $key => $value) {
            $urlString .= $key . '=' . $value . '&';
        }

        rtrim($urlString, '&');

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($postVars));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $urlString);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);

        curl_close($ch);

        return $result;
    }
}
