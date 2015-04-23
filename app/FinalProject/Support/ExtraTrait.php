<?php
namespace FinalProject\Support;

/**
 * My first trait!
 *
 * I use this pattern all the time when I don't want to make new trivial objects
 *
 * @package FinalProject\Support
 * @author Dan Richards
 */
trait ExtraTrait {

    protected $extra;

    /**
     *
     *
     * @return mixed
     */
    public function getExtra($key = null)
    {
        /**
         * Return the whole extra array
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
    public function setExtra($key = null, $data = null)
    {
        /**
         * Clear the array
         */
        if (is_null($key) && is_null($data)) {
            $this->extra = [];
            return $this;
        }

        /**
         * Or set an individual key
         */
        $this->extra['key'] = $data;
        return $this;
    }
}