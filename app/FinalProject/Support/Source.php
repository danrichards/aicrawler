<?php
namespace FinalProject\Support;
/**
 * A helper for Scraping
 *
 * Includes helper functions for various stuff.
 */
class Source {

    /**
     * Use curl to get a SourceResult object
     *
     * @param $url          The URL to download
     * @param $curlConfig   Try Config::curl() or some other associative array
     * @return SourceResult()
     */
    public static function curl($url, $curlConfig) {
        $ch = curl_init($url);
        curl_setopt_array($ch, $curlConfig);
        $result = new SourceResult(
            $url,
            curl_exec($ch),
            curl_getinfo($ch),
            curl_errno($ch),
            curl_error($ch)
        );
        curl_close($ch);
        return $result;
    }

    /**
     * Use file_get_contents to get a SourceResult object
     *
     * @param $url
     * @return SourceResult()
     */
    public static function fgc($url) {
        $content = file_get_contents($url);
        $result = new SourceResult(
            $url,
            file_get_contents($url),
            null,
            !$content,
            !$content ? "file_get_contents failed. Try curl." : ""
        );
        return $result;
    }

}