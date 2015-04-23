<?php

/**
 * Dan's Helper Functions
 *
 * @subpackage  Helpers
 * @category    Date Functions
 * @author      Dan Richards
 * @link        danrichardsri@gmail.com
 * @version     0.1
 * @see        Merged with CodeIgniter date_helper.
 */

/**
 * Converts excel date (days since 1/1/1900 to unix or desired php format
 */
if ( ! function_exists('date_excel_unix'))
{
    function date_excel_unix($date, $php_format = null) {

        if (!is_numeric($date) && $date < 500000)    // way to futuristic for us, return 0
            return 0;

        $unix = mktime(0, 0, 0, 1, 1, 1900) + ($date * 24 * 60 * 60);

        return is_null($php_format) ? $unix : date($php_format, $unix);

    }
}

/**
 * Converts excel date (days since 1/1/1900 to unix or desired php format
 */
if ( ! function_exists('date_unix_excel'))
{
    function date_unix_excel($date = null) {

        $date = is_null($date) ? time() : $date;

        if (!is_numeric($date))    // way to futuristic for us, return 0
            return 0;

        $date += (31557600 * 70); // 70 years worth of seconds

        return round($date / (24*60*60));

    }
}

/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		EllisLab Dev Team
 * @copyright		Copyright (c) 2008 - 2014, EllisLab, Inc.
 * @copyright		Copyright (c) 2014 - 2015, British Columbia Institute of Technology (http://bcit.ca/)
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @see         Dropped now()
 * @filesource
 */

/**
 * Number of days in a month
 *
 * Takes a month/year as input and returns the number of days
 * for the given month/year. Takes leap years into consideration.
 *
 * @access	public
 * @param	integer a numeric month
 * @param	integer	a numeric year
 * @return	integer
 */
if ( ! function_exists('days_in_month'))
{
    function days_in_month($month = 0, $year = '')
    {
        if ($month < 1 OR $month > 12)
        {
            return 0;
        }
        if ( ! is_numeric($year) OR strlen($year) != 4)
        {
            $year = date('Y');
        }
        if ($month == 2)
        {
            if ($year % 400 == 0 OR ($year % 4 == 0 AND $year % 100 != 0))
            {
                return 29;
            }
        }
        $days_in_month	= array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        return $days_in_month[$month - 1];
    }
}

/**
 * Converts a local Unix timestamp to GMT
 *
 * @access	public
 * @param	integer Unix timestamp
 * @return	integer
 */
if ( ! function_exists('local_to_gmt'))
{
    function local_to_gmt($time = '')
    {
        if ($time == '')
            $time = time();
        return mktime( gmdate("H", $time), gmdate("i", $time), gmdate("s", $time), gmdate("m", $time), gmdate("d", $time), gmdate("Y", $time));
    }
}

/**
 * Converts a MySQL Timestamp to Unix
 *
 * @access	public
 * @param	integer Unix timestamp
 * @return	integer
 */
if ( ! function_exists('mysql_to_unix'))
{
    function mysql_to_unix($time = '')
    {
        // We'll remove certain characters for backward compatibility
        // since the formatting changed with MySQL 4.1
        // YYYY-MM-DD HH:MM:SS
        $time = str_replace('-', '', $time);
        $time = str_replace(':', '', $time);
        $time = str_replace(' ', '', $time);
        // YYYYMMDDHHMMSS
        return  mktime(
            substr($time, 8, 2),
            substr($time, 10, 2),
            substr($time, 12, 2),
            substr($time, 4, 2),
            substr($time, 6, 2),
            substr($time, 0, 4)
        );
    }
}

/**
 * Unix to "Human"
 *
 * Formats Unix timestamp to the following prototype: 2006-08-21 11:35 PM
 *
 * @access	public
 * @param	integer Unix timestamp
 * @param	bool	whether to show seconds
 * @param	string	format: us or euro
 * @return	string
 */
if ( ! function_exists('unix_to_human'))
{
    function unix_to_human($time = '', $seconds = FALSE, $fmt = 'us')
    {
        $r  = date('Y', $time).'-'.date('m', $time).'-'.date('d', $time).' ';
        if ($fmt == 'us')
        {
            $r .= date('h', $time).':'.date('i', $time);
        }
        else
        {
            $r .= date('H', $time).':'.date('i', $time);
        }
        if ($seconds)
        {
            $r .= ':'.date('s', $time);
        }
        if ($fmt == 'us')
        {
            $r .= ' '.date('A', $time);
        }
        return $r;
    }
}

/**
 * Convert "human" date to GMT
 *
 * Reverses the above process
 *
 * @access	public
 * @param	string	format: us or euro
 * @return	integer
 */
if ( ! function_exists('human_to_unix'))
{
    function human_to_unix($datestr = '')
    {
        if ($datestr == '')
        {
            return FALSE;
        }
        $datestr = trim($datestr);
        $datestr = preg_replace("/\040+/", ' ', $datestr);
        if ( ! preg_match('/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}\s[0-9]{1,2}:[0-9]{1,2}(?::[0-9]{1,2})?(?:\s[AP]M)?$/i', $datestr))
        {
            return FALSE;
        }
        $split = explode(' ', $datestr);
        $ex = explode("-", $split['0']);
        $year  = (strlen($ex['0']) == 2) ? '20'.$ex['0'] : $ex['0'];
        $month = (strlen($ex['1']) == 1) ? '0'.$ex['1']  : $ex['1'];
        $day   = (strlen($ex['2']) == 1) ? '0'.$ex['2']  : $ex['2'];
        $ex = explode(":", $split['1']);
        $hour = (strlen($ex['0']) == 1) ? '0'.$ex['0'] : $ex['0'];
        $min  = (strlen($ex['1']) == 1) ? '0'.$ex['1'] : $ex['1'];
        if (isset($ex['2']) && preg_match('/[0-9]{1,2}/', $ex['2']))
        {
            $sec  = (strlen($ex['2']) == 1) ? '0'.$ex['2'] : $ex['2'];
        }
        else
        {
            // Unless specified, seconds get set to zero.
            $sec = '00';
        }
        if (isset($split['2']))
        {
            $ampm = strtolower($split['2']);
            if (substr($ampm, 0, 1) == 'p' AND $hour < 12)
                $hour = $hour + 12;
            if (substr($ampm, 0, 1) == 'a' AND $hour == 12)
                $hour =  '00';
            if (strlen($hour) == 1)
                $hour = '0'.$hour;
        }
        return mktime($hour, $min, $sec, $month, $day, $year);
    }
}

/**
 * Timezones
 *
 * Returns an array of timezones.  This is a helper function
 * for various other ones in this library
 *
 * @access	public
 * @param	string	timezone
 * @return	string
 */
if ( ! function_exists('timezones'))
{
    function timezones($tz = '')
    {
        // Note: Don't change the order of these even though
        // some items appear to be in the wrong order
        $zones = array(
            'UM12'		=> -12,
            'UM11'		=> -11,
            'UM10'		=> -10,
            'UM95'		=> -9.5,
            'UM9'		=> -9,
            'UM8'		=> -8,
            'UM7'		=> -7,
            'UM6'		=> -6,
            'UM5'		=> -5,
            'UM45'		=> -4.5,
            'UM4'		=> -4,
            'UM35'		=> -3.5,
            'UM3'		=> -3,
            'UM2'		=> -2,
            'UM1'		=> -1,
            'UTC'		=> 0,
            'UP1'		=> +1,
            'UP2'		=> +2,
            'UP3'		=> +3,
            'UP35'		=> +3.5,
            'UP4'		=> +4,
            'UP45'		=> +4.5,
            'UP5'		=> +5,
            'UP55'		=> +5.5,
            'UP575'		=> +5.75,
            'UP6'		=> +6,
            'UP65'		=> +6.5,
            'UP7'		=> +7,
            'UP8'		=> +8,
            'UP875'		=> +8.75,
            'UP9'		=> +9,
            'UP95'		=> +9.5,
            'UP10'		=> +10,
            'UP105'		=> +10.5,
            'UP11'		=> +11,
            'UP115'		=> +11.5,
            'UP12'		=> +12,
            'UP1275'	=> +12.75,
            'UP13'		=> +13,
            'UP14'		=> +14
        );
        if ($tz == '')
        {
            return $zones;
        }
        if ($tz == 'GMT')
            $tz = 'UTC';
        return ( ! isset($zones[$tz])) ? 0 : $zones[$tz];
    }
}
