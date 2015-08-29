<?php

namespace Dan\AiCrawler\Support;

/**
 * A container for a curl or file_get_contents result
 * @package AiCrawler\Support
 */
class SourceResult {

    protected $url;
    protected $source;
    protected $error;
    protected $errorNumber;
    protected $errorMessage;
    protected $header;

    /**
     * Initialize a new SourceResult object
     *
     * @param $url
     * @param null $source
     * @param null $header
     * @param null $errorNumber
     * @param null $errorMessage
     */
    function __construct($url, $source = null, $header = null, $errorNumber = null, $errorMessage = null) {
        $this->setUrl($url)
            ->setSource($source)
            ->setHeader($header)
            ->setErrors($errorNumber, $errorMessage);
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param mixed $source
     */
    public function setSource($source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @param mixed $header
     */
    public function setHeader($header)
    {
        $this->header = $header;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getErrorNumber()
    {
        return $this->errorNumber;
    }

    /**
     * @return mixed
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @return mixed
     */
    public function hasErrors()
    {
        return $this->error;
    }

    /**
     * @param mixed $errors
     */
    public function setErrors($number = null, $message = null)
    {
        if (is_null($number)) {
            $this->error = false;
            $this->errorNumber = $number;
            $this->errorMessage = $message;
        }
        return $this;
    }

}