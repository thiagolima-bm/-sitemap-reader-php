<?php

namespace Snowdog\SitemapReader\Service;

class SitemapParser
{
    /**
     * @var string
     */
    private $website;
    /**
     * @var array
     */
    private $pages = [];
    /**
     * @var array
     */
    private $errors = [];

    /**
     * @param string $sitemapUrl
     */
    public function parse($sitemapUrl = "")
    {
        $content = @file_get_contents($sitemapUrl);
        if (!$content) {
            $this->errors[] = "Invalid Content";
            return;
        }
        $xml = simplexml_load_string($content);

        if (!$xml) {
            $this->errors[] = "Invalid XML";
            return;
        }
        foreach ($xml->url as $urlElement) {
            $url = $urlElement->loc;
            if (!$this->website) {
                $this->website = parse_url($url, PHP_URL_HOST);
            }
            $this->pages[] = parse_url($url, PHP_URL_PATH);
        }
    }

    public function getWebsite()
    {
        return $this->website;
    }

    public function getPages()
    {
        return $this->pages;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function addError($error = "")
    {
        if ($error) {
            $this->errors[] = $error;
        }
    }
}

