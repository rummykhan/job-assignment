<?php

namespace AppBundle\Service\TradeTracker;

use Symfony\Component\DomCrawler\Crawler;

class FeedParser
{
    /**
     * @var array
     */
    private $attributes;

    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * @var XMLParser
     */
    private $parser;

    /**
     * FeedParser constructor receive two services.
     *
     * @param \Symfony\Component\DomCrawler\Crawler $crawler
     * @param \AppBundle\Service\TradeTracker\XMLParser $parser
     */
    public function __construct(Crawler $crawler, XMLParser $parser)
    {
        $this->crawler = $crawler;
        $this->parser = $parser;
    }

    /**
     * Set XML to parse.
     *
     * @param $xml
     * @return $this
     */
    public function setXML($xml)
    {
        $this->crawler->addXmlContent($xml);
        return $this;
    }

    /**
     * Set Attributes which are needed to parse.
     *
     * @param $attributes
     * @return $this
     */
    public function setDesiredAttributes($attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * Feed Parser Core function.
     *
     * @return array
     */
    public function parse()
    {
        $feedProductNodes = $this->getFeedProductNodes();

        $products = [];

        foreach ($feedProductNodes as $feedProductNode) {
            $products[] = $this->parseNode($feedProductNode);
        }

        return $products;

    }

    /**
     * Get Products DOM Nodes using XPath.
     *
     * @return Crawler
     */
    private function getFeedProductNodes()
    {
        return $this->crawler->filterXPath('products/*');
    }

    /**
     * Parse Each node separately.
     *
     * @param $xmlNode
     * @return mixed
     */
    private function parseNode($xmlNode)
    {
        $this->parser->parse($xmlNode, $results, $this->attributes);

        return $results;
    }
}