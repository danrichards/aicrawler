<?php

class Config {
    public static function curl() {
        $curl = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_USERAGENT      => "spider",
            CURLOPT_AUTOREFERER    => true,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_MAXREDIRS      => 10,
        ];

        return $curl;
    }

    /**
     * Relevant Scoring Content Tags
     *
     * @return array
     */
    public static function rscTags() {
        return array_keys(Config::rscTagWeight());
    }

    /**
     * Relevant Scoring Content Tags with their respective weights
     *
     * Scoring 0~1 on likeliness to appear in an article's content body
     *
     * @return array
     */
    public static function rscTagWeight($tag = null) {
        $rsc = [
            'p' => 1,
            'blockquote' => 0.5,
            'strike' => 0.3,
            'code' => 0.3,
            'mark' => 0.3,
            'cite' => 0.2,
            'em' => 0.1,
            'b' => 0.1,
            'i' => 0.1,
            'u' => 0.1,
            'q' => 0.1,
            's' => 0.1,
            'strong' => 0.1,
            'sub' => 0.1,
            'sup' => 0.1,
            'h2' => 0.1,
            'h3' => 0.1,
            'h4' => 0.05,
            'center' => 0.05,
        ];
        if (is_null($tag))
            return $rsc;
        elseif (array_key_exists($tag, $rsc))
            return $rsc[$tag];
        else
            return 0;
    }

    /**
     * Relevant Scoring Recurrence bonus
     *
     * ie. If the same tag appears multiple times in a row, should we score it higher?
     *
     * @param null $tag
     */
    public static function rsRecurrence($tag = null) {

        $rsr = [
            'p' => 0.25,
            'article' => 0.25,
            'div' => 0.1
        ];
        if (is_null($tag))
            return $rsr;
        elseif (array_key_exists($tag, $rsr))
            return $rsr[$tag];
        else
            return 0;
    }
}
