<?php

namespace AppBundle\Service\TradeTracker\Fields;


use AppBundle\Service\TradeTracker\Contracts\ParserInterface;
use DOMNode;

class ProductIDParser implements ParserInterface
{
    /**
     * @inheritdoc
     */
    public function getValue($attribute, DOMNode $node)
    {
        foreach ($node->childNodes as $childNode) {

        }
    }
}