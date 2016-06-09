<?php

namespace Dan\AiCrawler;

/**
 * Class Scoreable
 *
 * @package Dan\AiCrawler\Support
 * @author Dan Richards <danrichardsri@gmail.com>
 */
trait Scoreable {

    /**
     * An associative array (items) of associative arrays (data points).
     *
     * @var array
     */
    protected $scores = [];
    protected $min = false, $max = false;

    /**
     * Get the score array for a specific context
     *
     * @param $item
     * @param null $dataPoint
     * @param int $default
     *
     * @return array|float
     */
    public function dataPoint($item, $dataPoint = null, $default = 0)
    {
        return $this->hasDataPoint($item, $dataPoint)
            ? $this->scores[$item][$dataPoint] : $default;
    }

    /**
     * Return the data points for an item.
     *
     * @param $item
     * @return null
     */
    public function item($item) {
        return $this->hasItem($item) ? $this->scores[$item] : null;
    }

    /**
     * Return an array of the items we're tracking data points.
     *
     * @return array
     */
    public function items()
    {
        return array_keys($this->scores);
    }

    /**
     * Return all data points for a node or all potential data points.
     *
     * @param null $item
     *
     * @return array
     */
    public function dataPoints($item = null)
    {
        if (is_null($item)) {
            $dataPoints = [];
            foreach ($this->items() as $item) {
                $dataPoints = array_merge($dataPoints, array_keys($this->scores[$item]));
            }
            return array_unique($dataPoints);
        } else {
            return $this->hasItem($item) ? array_keys($this->scores[$item]) : [];
        }
    }

    /**
     * Get the Score for one of our items or all of them
     *
     * @param $itemOrItems
     *
     * @param null $dataPoints
     * @return float
     */
    public function total($itemOrItems = null, $dataPoints = null)
    {
        /**
         * Sum everything in scores.
         */
        if (is_null($itemOrItems) && is_null($dataPoints)) {
            $sum = 0;
            foreach ($this->scores as $item => $dataPoints) {
                $sum += array_sum($dataPoints ?: []);
            }
            return $sum;
        }

        /**
         * Sum the data points provided for all items.
         */
        if (is_null($itemOrItems)) {
            $sum = 0;
            foreach ($this->scores as $item => $dps) {
                $sum += $this->total($item, $dataPoints);
            }
            return $sum;
        }

        /**
         * Sum all data points in the items provided.
         */
        if (is_null($dataPoints)) {
            $itemOrItems = (array) $itemOrItems;
            $sum = 0;
            foreach($itemOrItems as $item) {
                $sum += array_sum($this->item($item) ?: []);
            }
            return $sum;
        }

        /**
         * Sum the data points provided with the items provided.
         */
        $itemOrItems = (array) $itemOrItems;
        $dataPoints = (array) $dataPoints;
        $sum = 0;
        foreach ($itemOrItems as $item) {
            if ($this->hasItem($item)) {
                $dpi = array_intersect_key($this->item($item), $dataPoints);
                $sum += array_sum($dpi ?: []);
            }
        }
    }

    /**
     * Score within the acceptable bounds
     *
     * @param $score
     *
     * @return float
     */
    private function validDataPoint($score) {
        if ($this->getMax() !== false && $score >= $this->getMax()) {
            return $this->getMax();
        } elseif ($this->getMin() !== false && $score <= $this->getMin()) {
            return $this->getMin();
        } else {
            return $score;
        }
    }

    /**
     * Set the Score for one of our contexts
     *
     * @param $item
     * @param $dataPoint
     * @param mixed $value
     *
     * @return $this
     */
    public function setDataPoint($item, $dataPoint, $value = 0)
    {
        $this->scores[$item][$dataPoint] = $this->validDataPoint($value);
        return $this;
    }

    /**
     * Remove scoring for any item.
     *
     * @param $item
     *
     * @return $this
     */
    public function removeItem($item)
    {
        if ($this->hasItem($item)) {
            unset($this->scores[$item]);
        }
        return $this;
    }

    /**
     * Remove scoring for any item.
     *
     * @param string $item
     * @param array $dataPoints
     *
     * @return $this
     */
    public function removeDataPoints($item, array $dataPoints = null)
    {
        if ($this->hasItem($item)) {
            if (!is_null($dataPoints)) {
                $this->scores[$item] = [];
            } else {
                $this->scores[$item] = array_diff_key(
                    $this->scores[$item],
                    array_fill_keys($dataPoints, null)
                );
            }
        }
        return $this;
    }

    /**
     * Do we have a data point in our scoring?
     *
     * @param $item
     * @param $dataPoint
     *
     * @return bool
     */
    public function hasDataPoint($item, $dataPoint)
    {
        return $this->hasItem($item) && isset($this->scores[$item][$dataPoint])
            && is_numeric($this->scores[$item][$dataPoint]);
    }

    /**
     * Are we tracking data points for a certain item yet?
     *
     * @param $item
     * @return bool
     */
    public function hasItem($item)
    {
        return array_key_exists($item, $this->scores);
    }

    /**
     * @return mixed
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * @param mixed $min
     *
     * @return $this Object
     */
    public function setMin($min)
    {
        $this->min = $min;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * @param mixed $max
     *
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
     * @param mixed $min
     * @param mixed $max
     *
     * @return $this
     */
    public function setMinMax($min, $max)
    {
        $this->min = $min;
        $this->max = $max;
        return $this;
    }

    /**
     * @return array
     */
    public function getScores()
    {
        return $this->scores;
    }
}