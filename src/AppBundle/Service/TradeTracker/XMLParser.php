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

    /**
     * @var MapperInterface
     */
    private $mapper;

    private $xml;

    public function __construct(Crawler $crawler, MapperInterface $mapper)
    {
        $this->crawler = $crawler;
        $this->mapper = $mapper;
    }

    public function setXML($xml)
    {
        $this->xml = $xml;
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

        foreach ($xmlProducts as $xmlProduct) {
            $products[] = $this->parseNode($xmlProduct);
        }

        return $products;

    }

    private function getProducts()
    {
        return $this->crawler->filterXPath('products');
    }

    private function parseNode(DOMNode $xmlProduct)
    {
        $array = (new DomNodeParser())->parse($xmlProduct);
        dump($array, $this->xml);exit;

        $product = new Product();

        foreach ($this->attributes as $attribute) {
            if ($parser = $this->mapper->map($attribute)) {
                $product->setAttribute($attribute, $parser->getValue($attribute, $xmlProduct));
            }
        }

        return $product;
    }
}