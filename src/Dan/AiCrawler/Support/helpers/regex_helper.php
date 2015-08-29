<?php

/**
 * Dan's RegEx Helper Functions
 *
 * @subpackage  Helpers
 * @category    Regular Expressions
 * @author      Dan Richards
 * @link        danrichardsri@gmail.com
 * @version     0.1
 * @why         Because I can't bust these out on the fly.
 * @see         stackoverflow.com
 */

/**
 * Get only the digits from a string
 *
 * @param $string
 * @return mixed
 */
if ( ! function_exists('regex_digits')) {
    function regex_digits($string)
    {
        return preg_replace("/[^0-9]/", "", $string);
    }
}

/**
 * Format a string to phone
 */
if ( ! function_exists('regex_phone'))
{
    function regex_phone($string) {
        $string = regex_digits($string);
        if (strlen($string) == 7)
            return substr($string,0,3) . "-" . substr($string, 3);
        else if (strlen($string) == 10)
            return substr($string,0,3) . "-" . substr($string, 3,3) . "-" . substr($string, 6);
        else
            return $string;
    }
}

/**
 * Remove extraneous whitespace
 */
if ( ! function_exists('regex_remove_extraneous_whitespace'))
{
    function regex_remove_extraneous_whitespace($text, $replace = " ") {
        return preg_replace('/\s+/', $replace, $text);
    }
}

/**
 * Remove all whitespace
 */
if ( ! function_exists('regex_remove_whitespace'))
{
    function regex_remove_whitespace($text) {
        return preg_replace('/[\n\r\s]+/', "", $text);
    }
}

/**
 * Letter Numbers and Hyphens
 */
if ( ! function_exists('regex_letters_numbers_hyphens'))
{
    function regex_letters_numbers_hyphens($text, $force_lowercase = true) {
        $text = $force_lowercase ? strtolower($text) : $text;
        return preg_replace('([A-Za-z0-9\-]+)', '', $text);
    }
}

/**
 * Letter, Number, Hyphens and Underscores
 */
if ( ! function_exists('regex_letters_numbers_hyphens_underscores'))
{
    function regex_letters_numbers_hyphens_underscores($text, $force_lowercase = true) {
        $text = $force_lowercase ? strtolower($text) : $text;
        return preg_replace('([A-Za-z0-9\-\_]+)', '', $text);
    }
}

/**
 * Generates a slug
 */
if ( ! function_exists('regex_slug'))
{
    function regex_slug($string, $separator = '-') {

        $string = trim($string);
        $string = strtolower($string);
        $string = str_replace("_", "-", $string);
        $string = preg_replace('/[\s-]+/', $separator, $string);
        $string = preg_replace('/[\s-]+/', $separator, $string);
        $string = preg_replace("/[^0-9a-zA-Z-]/", '', $string);

        return $string;
    }
}

/**
 * Remove HTML tags and optionally remove the content within them.
 */
if ( ! function_exists('regex_remove_html')) {
    function regex_remove_html($string, $andContents = false)
    {
        if ($andContents) {
            return preg_replace('/<.*[^>]*>.*<\/.*>/', '', $string);
//            return preg_replace('/<a[^>]*>.*<\/a>/', '', $string);
        } else {
            return strip_tags($string);
        }
    }
}

/**
 * Remove HTML tags and optionally remove the content within them.
 */
if ( ! function_exists('regex_remove_html_tags')) {
    function regex_remove_html_tags($string, $tags, $andContents = false)
    {
        if ($andContents) {
            return is_array($tags)
                ? preg_replace('/<('.implode("|", $tags).')[^>]*>.*<\/('.implode("|", $tags).')>/', '', $string)
                : preg_replace('/<'.$tags.'[^>]*>.*<\/'.$tags.'>/', '', $string);
        } else {
            return (is_array($tags))
                ? preg_replace('/<('.implode("|", $tags).')[^>]*>/', '', $string)
                : preg_replace('/<'.$tags.'[^>]*>/', '', $string);
        }
    }
}

/**
 * Is the string in the set?
 */
if ( ! function_exists('regex_set_contains')) {
    function regex_set_contains($string, $set, $ignoreCase = true)
    {
        $set = (array) $set;
        $set = implode("|", $set);
        if ($ignoreCase) {
            $string = strtolower($string);
            $set = strtolower($set);
        }
        return preg_match('~\b('.$set.')\b~i', $string);
    }
}

/**
 * Is the string in the set or within a substring of a element?
 */
if ( ! function_exists('regex_set_contains_substr')) {
    function regex_set_contains_substr($string, $set, $ignoreCase = true)
    {
        $set = (array) $set;
        $set = implode("|", $set);
        if ($ignoreCase) {
            $string = strtolower($string);
            $set = strtolower($set);
        }
        return preg_match('~\b.*('.$set.').*\b~i', $string);
    }
}
