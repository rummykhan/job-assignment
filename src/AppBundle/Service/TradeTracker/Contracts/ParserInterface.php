<?php

namespace AppBundle\Service\TradeTracker\Contracts;


use DOMNode;

interface ParserInterface
{
    /**
     * This function check DOMNode and get the desired attribute
     *
     * @param $attribute
     * @param DOMNode $node
     * @return mixed|null
     */
    public function getValue($attribute, DOMNode $node);
}