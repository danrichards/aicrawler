<?php

namespace Dan\AiCrawler\Support;

use Dan\AiCrawler\Support\Exceptions\SourceNotFoundException;

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
    public static function curl($url, $curlConfig, $suppressExceptions = false) {
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
        if (!$suppressExceptions && !$result->getSource())
            throw new SourceNotFoundException("Unable to download web page. Check your URL and consider using file_get_contents to download source.");
        return $result;
    }

    /**
     * Use file_get_contents to get a SourceResult object
     *
     * @param $url
     * @return SourceResult()
     */
    public static function fgc($url, $suppressExceptions = false) {
        $content = file_get_contents($url);
        $result = new SourceResult(
            $url,
            file_get_contents($url),
            null,
            !$content,
            !$content ? "file_get_contents failed. Try curl." : ""
        );

        if (!$suppressExceptions && !$result->getSource())
            throw new SourceNotFoundException("Unable to download web page. Check your URL and consider using curl to download source.");

        return $result;
    }

    /**
     * Try curl then file_get_contents if curl fails.
     *
     * @param $url
     * @param $curlConfig
     * @param bool $suppressExceptions
     */
    public static function both($url, $curlConfig, $suppressExceptions = false) {
        $result = self::curl($url, $curlConfig, true);
        if (!$result->getSource())
            $result = self::fgc($url, true);
        if (!$suppressExceptions && !$result->getSource())
            throw new SourceNotFoundException("Unable to download web page. Check your URL. Both curl and file_get_contents failed.");
        return $result;
    }

}