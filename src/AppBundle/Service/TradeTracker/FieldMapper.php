<?php

namespace AppBundle\Service\TradeTracker;


use AppBundle\Service\TradeTracker\Contracts\MapperInterface;
use AppBundle\Service\TradeTracker\Contracts\ParserInterface;
use AppBundle\Service\TradeTracker\Fields\CategoryParser;
use AppBundle\Service\TradeTracker\Fields\CurrencyParser;
use AppBundle\Service\TradeTracker\Fields\DescriptionParser;
use AppBundle\Service\TradeTracker\Fields\ImageURLParser;
use AppBundle\Service\TradeTracker\Fields\NameParser;
use AppBundle\Service\TradeTracker\Fields\PriceParser;
use AppBundle\Service\TradeTracker\Fields\ProductIDParser;
use AppBundle\Service\TradeTracker\Fields\ProductURLParser;

class FieldMapper implements MapperInterface
{
    protected $map = [
        'productID' => ProductIDParser::class,
        'name' => NameParser::class,
        'description' => DescriptionParser::class,
        'price' => PriceParser::class,
        'currency' => CurrencyParser::class,
        'categories' => CategoryParser::class,
        'productURL' => ProductURLParser::class,
        'imageURL' => ImageURLParser::class
    ];

    /**
     * @inheritdoc
     */
    public function map($fieldName)
    {
        return $this->map[$fieldName] ?? null;
    }
}