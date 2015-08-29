<?php

namespace Dan\AiCrawler\Support;

trait ScoreTrait {

    protected $scores = [];
    protected $min = false, $max = false;

    /**
     * Get the score array for a specific context
     *
     * @return double|array
     */
    public function getScore($context, $point = null)
    {
        /**
         * An individual data point
         */
        if (!is_null($point)) {
            $score = (array_key_exists($context, $this->scores) && array_key_exists($point, $this->scores[$context]))
                ? $this->scores[$context][$point]
                : 0;
            return $this->validScore($score);
        }

        /**
         * The data points for the context
         */
        return array_key_exists($context, $this->scores) ? $this->scores[$context] : null;
    }

    /**
     * Get the Score for one of our contexts
     *
     * @return double
     */
    public function getScoreTotal($context)
    {
        $score = array_key_exists($context, $this->scores) ? array_sum($this->scores[$context]) : 0;
        return $this->validScore($score);
    }

    /**
     * Score within the acceptable bounds
     *
     * @param $score
     */
    private function validScore($score) {
        if ($this->getMax() !== false && $score >= $this->getMax())
            return $this->getMax();
        if ($this->getMin() !== false && $score <= $this->getMin())
            return $this->getMin();
        else
            return $score;
    }

    /**
     * Set the Score for one of our contexts
     *
     * @param mixed $score
     */
    public function setScore($context, $point, $score)
    {
        $this->scores[$context][$point] = $score;
        return $this;
    }

    /**
     * @return double
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * @param double $min
     * @return $this Object
     */
    public function setMin($min)
    {
        $this->min = $min;
        return $this;
    }

    /**
     * @return double
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * @param double $max
     * @return $this Object
     */
    public function setMax($max)
    {
        $this->max = $max;
        return $this;
    }

    /**
     * Set the Min and Max
     *
     * @param double $max
     * @return $this
     */
    public function setMinMax($max)
    {
        $this->max = $max;
        return $this;
    }
}