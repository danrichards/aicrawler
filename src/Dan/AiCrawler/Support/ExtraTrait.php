<?php

namespace Dan\AiCrawler\Support;

/**
 * My first trait!
 *
 * I use this pattern all the time when I don't want to make new trivial objects or collections.
 *
 * @package AiCrawler\Support
 * @author Dan Richards
 */
trait ExtraTrait {

    protected $extra = null;

    /**
     *
     *
     * @return mixed
     */
    public function getExtra($key = null)
    {
        /**
         * Return the whole array
         */
        if (is_null($key))
            return $this->extra;

        /**
         * Or a specific data point
         */
        return (array_key_exists($key, $this->extra)) ? $this->extra[$key] : null;
    }

    /**
     * We may want to store specific data points for review later.
     *
     * @param mixed $extra
     */
    public function setExtra($keyOrArray = null, $data = null)
    {
        /**
         * Clear the array
         */
        if (is_null($keyOrArray) && is_null($data)) {
            $this->extra = [];
            return $this;
        }

        /**
         * Set an entire array
         */
        if (is_null($data) && is_array($keyOrArray))
            $this->extra = array_merge($this->extra, $keyOrArray);

        /**
         * Or set an individual key
         */
        $this->extra[$keyOrArray] = $data;
        return $this;
    }
}