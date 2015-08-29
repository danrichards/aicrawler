<?php

/**
 * Get the root domain name of the URL
 *
 * @param $url
 * @return mixed|string
 */
function parseDomainRoot($url) {
    $domain = parse_url($url, PHP_URL_HOST);

    $last_period = strrpos($domain, '.');
    if ($last_period === false)
        return $domain;

    $start = 0;
    $domain_without_subdomains = $domain;
    $period = strpos($domain, '.', $start);

    while($period !== false && $period < $last_period):
        $domain_without_subdomains = substr($domain, $period + 1);
        $period = strpos($domain, '.', $period + 1);
    endwhile;

    return $domain_without_subdomains;
}

/**
 * Get the domain of the URL
 *
 * @param $url
 * @return mixed
 */
function parseDomain($url)
{
    return parse_url($url, PHP_URL_HOST);
}

/**
 * Determine if a URL is http or https
 *
 * @param $url
 * @return string
 */
function parseProtocol($url)
{
    return preg_match("/https:\/\//", $url) ? "https://" : "http://";
}

/**
 * Remove the protocol from the URL
 *
 * @param $url
 * @return mixed
 */
function urlDropProtocol($url)
{
    return preg_replace("/http(s?):\/\//", "", $url);
}

/**
 * Validate email address
 *
 * @access	public
 * @return	bool
 */
function validEmail($address)
{
    return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $address)) ? FALSE : TRUE;
}

/**
 * Get the username from an email address
 *
 * @param $email
 * @return array|bool
 */
function emailUsername($email)
{
    $username = validEmail($email) ? explode('@', $email) : false;
    return !$username ? $username : $username[0];
}

/**
 * Get the email from the message format.
 *
 * @param $email
 * @return bool|mixed|string
 */
function emailFromMessage($email)
{
    $email = str_replace(" ", "", $email);
    if (validEmail($email)):
        return $email;
    elseif (strpos($email, '<') !== false && strpos($email, '>') !== false):
        return substr($email, strpos($email, '<')+1, strpos($email, '>') - strpos($email, '<')-1);
    else:
        return false;
    endif;
}