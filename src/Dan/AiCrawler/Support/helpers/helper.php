<?php

/**
 * Dan's Helper Functions
 *
 * @subpackage  Helpers
 * @category    Helpers
 * @author      Dan Richards
 * @link        danrichardsri@gmail.com
 * @version     3.3
 * @see         added first, last and updated doc blocks, removed segment functions
 */

/**
 * Convert an array into an object
 */
if ( ! function_exists('objectify'))
{
    function objectify($array) {
        return json_decode(json_encode($array), FALSE);
    }
}

/**
 * take an assoc array and unset a list of keys
 */
if ( ! function_exists('unset_keys'))
{
    function unset_keys(&$array, $keys) {

        if (is_array($array)):
            foreach($keys as $k):
                if (array_key_exists($k, $array))
                    unset($array[$k]);
            endforeach;
        endif;
    }
}

/**
 * Return true if false, null, or empty
 */
if ( ! function_exists('is_empty'))
{
    function isEmpty($mixed = null) {

        if (is_object($mixed)) return false;

        if (is_null($mixed)) return true;

        if (is_array($mixed))
            return (count($mixed) == 0);

        if ($mixed === false)
            return true;

        return false;
    }
}

/**
 * Dump a variable, in as few characters as possible
 */
if ( ! function_exists('microdump'))
{
    function microdump($data, $return = false, $begin = "[", $end = "]", $sep = ", ") {
        $data = !is_array($data) ? (array) $data : $data;
        $text = $begin;
        $counter = 0;
        foreach($data as $i => $d) {
            $index = (!is_numeric($i) || $counter != $i) ? $i."=" : "";
            if (is_array($d))
                $text .= $index.microdump($d, true, $begin, $end, $sep);
            elseif (is_object($d))
                $text .= $index.microdump($d, true, $begin, $end, $sep);
            else
                $text .= $index.$d;
            if ($counter != (count($data) - 1))
                $text .= $sep;
            $counter ++;
        }
        $text .= $end;
        if ($return)
            return $text;
        else
            print $text;
    }
}

/**
 * Output / string with the pre tag
 */
if ( ! function_exists('pre'))
{
    function pre($message, $string = false) {
        if ($string) ob_start();

        echo "<pre>";
        print_r($message);
        echo "</pre><br />";

        return $string ? ob_get_clean() : $message;
    }
}

/**
 * var_dump / string with the pre tag
 */
if ( ! function_exists('pred'))
{
    function pred($message, $string = false) {
        if ($string) ob_start();

        echo "<pre>";
        var_dump($message);
        echo "</pre><br />";

        return $string ? ob_get_clean() : $message;
    }
}

/**
 * var_dump with the pre tag and exit
 */
if ( ! function_exists('prede'))
{
    function prede($message) {
        echo "<pre>";
        var_dump($message);
        echo "</pre><br />";
        exit;
    }
}

/**
 * Output / prettify an associative array where possible
 */
if ( ! function_exists('pref'))
{
    function pref($array, $item = null, $linebreak = "", $type = null)
    {

        if (!is_array($array) || count($array) == 0)
            return "";

        $content = "";
        foreach($array as $value):

            if (is_null($item)):

                switch ($type):
                    case "image":
                        $content .= "<a href=\"".$value."\"><img src=\"".$value."\" style=\"border: none; max-width:350px; max-height:200px;\" /></a>".$linebreak;
                        break;
                    case "href":
                        $content .= "<a href=\"".$value."\">".$value."</a>".$linebreak;
                        break;
                    default:
                        $content .= $value.$linebreak;
                endswitch;

            else:

                switch ($type):
                    case "image":
                        $content .= "<a href=\"".$value[$item]."\"><img src=\"".$value[$item]."\" style=\"border: none; max-width:350px; max-height:200px;\" /></a>".$linebreak;
                        break;
                    case "href":
                        $content .= "<a href=\"".$value[$item]."\">".$value[$item]."</a>".$linebreak;
                        break;
                    default:
                        $content .= $value[$item].$linebreak;
                endswitch;

            endif;
        endforeach;

        echo substr($content, 0, strlen($content) - strlen($linebreak));

    }
}

/**
 * Output / prettify an associative array to a list where possible
 */
if ( ! function_exists('prel'))
{
    function prel($array, $item = null, $type = null)
    {
        if (!is_array($array) || count($array) == 0)
            return "";
        $content = "<ul>";
        foreach($array as $value):
            if (is_null($item)):
                switch ($type):
                    case "image":
                        $content .= "<li><a href=\"".$value."\"><img src=\"".$value."\" style=\"border: none; max-width:350px; max-height:200px;\" /></a></li>";
                        break;
                    case "href":
                        $content .= "<li><a href=\"".$value."\">".$value."</a></li>";
                        break;
                    default:
                        $content .= "<li>".print_r($value, true)."</li>";
                endswitch;
            else:
                switch ($type):
                    case "image":
                        $content .= "<li><a href=\"".$value[$item]."\"><img src=\"".$value[$item]."\" style=\"border: none; max-width:350px; max-height:200px;\" /></a></li>";
                        break;
                    case "href":
                        $content .= "<li><a href=\"".$value[$item]."\">".$value[$item]."</a></li>";
                        break;
                    default:
                        $content .= "<li>".print_r($value[$item], true)."</li>";
                endswitch;
            endif;
        endforeach;
        $content .= "</ul>";
        echo $content;
    }
}

/**
 * Output / prettify an associative array to a table where possible
 */
if ( ! function_exists('pret'))
{
    function pret($array, $keys_as_cols = false)
    {
        if (!is_array($array) || count($array) == 0)
            return "";
        echo "<table class=\"pret\">";
        if ($keys_as_cols):
            $cols = array_keys($array);
            echo "<thead>";
            echo "<tr><th>"; echo implode($cols, "</th><th>"); echo "</th></tr>";
            echo "</thead>";

            echo "<tbody><tr>";
            foreach($array as $key => $value):
                echo "<td>" . pred($value, true) . "</td>";
            endforeach;
            echo "</tr></tbody>";
        else:
            echo "<thead><tr><th>Field</th><th>Value</th></tr></thead>";
            echo "<tbody>";
            foreach($array as $key => $value):
                echo "<tr><td>$key</td><td>".print_r($value, true)."</td></tr>";
            endforeach;
            echo "</tbody>";
        endif;
        echo "</table>";
    }
}