<?php

namespace AppBundle\Service\TradeTracker;


use AppBundle\Service\TradeTracker\Contracts\MapperInterface;
use DOMDocument;
use DOMNode;
use Symfony\Component\DomCrawler\Crawler;

class XMLParser
{
    /**
     * @var array
     */
    private $attributes;

    /**
     * @var Crawler
     */
    private $crawler;

    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    public function setXML($xml)
    {
        $this->crawler->addXmlContent($xml);
        return $this;
    }

    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }

    public function parse()
    {
        $xmlProducts = $this->getProducts();

        $products = [];

        foreach ($xmlProducts as $index => $xmlProduct) {
            $products[] = $this->parseNode($xmlProduct);
        }

        return $products;

    }

    private function getProducts()
    {
        return $this->crawler->filterXPath('products/*');
    }

    private function parseNode(DOMNode $xmlProduct)
    {
        $results = [];

        (new DomNodeParser())->parse($xmlProduct, $results);

        return $results;
    }
}