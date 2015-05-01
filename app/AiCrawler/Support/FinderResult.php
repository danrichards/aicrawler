<?php namespace AiCrawler\Support;

/**
 * Return a simple class that contains a DomCrawler / Articrawl result
 *
 * @package AiCrawler\Support
 */
class FinderResult {

    protected $nodes;
    protected $found;
    protected $message;
    protected $extra;

    /**
     * Initialize our result
     */
    function __construct($node = null, $found = false, $message = "") {
        $this->node = $node;
        $this->found = $found;
        $this->message = $message;
    }

    /**
     * @return null
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * @param null $node
     */
    public function setNode($node)
    {
        $this->node = $node;
    }

    /**
     * @return boolean
     */
    public function isFound()
    {
        return $this->found;
    }

    /**
     * @param boolean $found
     */
    public function setFound($found)
    {
        $this->found = $found;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getExtra()
    {
        return $this->extra;
    }

    /**
     * @param mixed $extra
     */
    public function setExtra($extra)
    {
        $this->extra = $extra;
    }

}