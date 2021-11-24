<?php

/**
 * Generic cURL requests class
 * 
 * This creates GET and POST requests on behalf of PHP with a standardized stdClass 
 * response that is easy to test for and reports cURL specific errors easily.
 * 
 * - Also easy to turn off SSL Verification for testing
 * - Quick setting of extra cURL options with default overrides
 * - Easy to add custom headers
 * 
 * @author Martyr2
 * @copyright 2021 The Coders Lexicon
 * @link https://www.coderslexicon.com
 */

 class CurlRequests 
 {
    /**
     * Executes a GET request on URL with specified headers
     *
     * @param string $url - URL to post to
     * @param array $headers - Optional headers to add to request
     * @param array $options - Optional cURL "setopt" options to configure the request
     * @param boolean $sslVerify - Optional flag to turn off SSL verification (keep on in production)
     * @return stdClass Returns stdClass with status code and content or throws an exception if cURL error.
     * @throws Exception If there was a cURL error
     */
    public static function get(string $url, array $headers = [], array $options = [], bool $sslVerify = true) 
    {
        // Defaults
        $defaultOptions = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYHOST => $sslVerify ? 2 : 0,
            CURLOPT_SSL_VERIFYPEER => $sslVerify
        ];

        $setOptions = $options + $defaultOptions;

        $h = [];

        foreach ($headers as $headerName => $headerValue) {
            $h[] = "$headerName: $headerValue";
        }

        $setOptions[CURLOPT_HTTPHEADER] = $h;

        $ch = curl_init();
        curl_setopt_array($ch, $setOptions);

        $content = curl_exec($ch);

        if ($content === false) {
            $errNo = curl_errno($ch);
            throw new Exception("cURL error: " . curl_strerror($errNo), $errNo);
        }

        $sc = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $std = new \stdClass();
        $std->status_code = $sc;
        $std->content = $content;

        return $std;
    }

    /**
     * Executes a POST request on URL with body data and with any specified headers
     *
     * @param string $url - URL to post on
     * @param string|array $data - Encoded string of data or array of parameters to post as the body
     * @param array $headers - Optional headers to add to request
     * @param array $options - Optional cURL "setopt" options to configure the request
     * @param boolean $sslVerify - Optional flag to turn off SSL verification (keep on in production)
     * @return stdClass Returns stdClass with status code and content or throws an exception if cURL error.
     * @throws Exception If there was a cURL error
     */
    public static function post(string $url, $data, array $headers = [], array $options = [], bool $sslVerify = true) 
    {
        // Defaults
        $defaultOptions = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_SSL_VERIFYHOST => $sslVerify ? 2 : 0,
            CURLOPT_SSL_VERIFYPEER => $sslVerify,
            CURLOPT_POSTFIELDS => is_array($data) ? http_build_query($data) : $data
        ];

        $setOptions = $options + $defaultOptions;

        $h = [];

        foreach ($headers as $headerName => $headerValue) {
            $h[] = "$headerName: $headerValue";
        }

        $setOptions[CURLOPT_HTTPHEADER] = $h;

        $ch = curl_init();
        curl_setopt_array($ch, $setOptions);                                                                

        $content = curl_exec($ch);

        if ($content === false) {
            $errNo = curl_errno($ch);
            throw new Exception("cURL error: " . curl_strerror($errNo), $errNo);
        }

        $sc = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $std = new \stdClass();
        $std->status_code = $sc;
        $std->content = $content;

        return $std;
    }
}
