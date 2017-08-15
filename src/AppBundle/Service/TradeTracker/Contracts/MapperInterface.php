<?php

namespace AppBundle\Service\TradeTracker\Contracts;


interface MapperInterface
{
    /**
     * @param $fieldName
     * @return ParserInterface|null
     */
    public function map($fieldName);
}