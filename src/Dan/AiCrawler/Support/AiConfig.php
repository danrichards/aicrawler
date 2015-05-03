<?php namespace Dan\AiCrawler\Support;

class AiConfig {

    /**
     * Initialize the AiConfig object.
     *
     * @param null $config
     */
    function __construct($config = null) {
        if (!is_null($config) && is_array($config))
            $this->config($config);
    }

    /**
     * In case you really want to cut down on the boiler-plate.
     *
     * @param $config
     */
    public function config($config) {
        foreach ($config as $property => $value)
            $this->{$property} = $value;
        return $this;
    }

    /**
     * Get a config item.
     *
     * @param $item
     */
    public function get($item) {
        return $this->{$item};
    }

    protected $curl = [
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

    protected $exampleSource = '
            <!doctype html>
            <html>
                <head>
                    <title>Example Domain</title>

                    <meta charset="utf-8" />
                    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
                    <meta name="viewport" content="width=device-width, initial-scale=1" />
                    <style type="text/css">
                        body {
                            background-color: #f0f0f2;
                            margin: 0;
                            padding: 0;
                            font-family: "Open Sans", "Helvetica Neue", Helvetica, Arial, sans-serif;
                        }
                        div {
                            width: 600px;
                            margin: 5em auto;
                            padding: 50px;
                            background-color: #fff;
                            border-radius: 1em;
                        }
                        a:link, a:visited {
                            color: #38488f;
                            text-decoration: none;
                        }
                        @media (max-width: 700px) {
                            body {
                                background-color: #fff;
                            }
                            div {
                                width: auto;
                                margin: 0 auto;
                                border-radius: 0;
                                padding: 1em;
                            }
                        }
                    </style>
                </head>

                <body>
                    <div>
                        <h1>Example Domain</h1>
                        <p>This domain is established to be used for illustrative examples in documents. You may use this
                        domain in examples without prior coordination or asking for permission.</p>
                        <p><a href="http://www.iana.org/domains/example">More information...</a></p>
                    </div>
                </body>
            </html>';

}
