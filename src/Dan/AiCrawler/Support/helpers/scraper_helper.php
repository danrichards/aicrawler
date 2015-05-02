<?php

/**
* Remove HTML tags and optionally remove the content within them.
*/
if ( ! function_exists('lexicalPenalty')) {
    function lexicalPenalty($string, $penalty, $characters = 0, $words = 0, $linebreaks = 0) {
        $numWords = str_word_count($string);
        $numBreaks = substr_count($string, "\n");
        $numCharacters = strlen($string);

        if ($numCharacters < $characters || $numWords < $words || $numBreaks < $linebreaks)
            return $penalty;
        return 0;
    }
}
