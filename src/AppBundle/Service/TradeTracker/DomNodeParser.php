<?php

namespace AppBundle\Service\TradeTracker;


class DomNodeParser
{

    public function parse($node, &$results = [])
    {
        foreach ($node->childNodes as $node) {

            // for DOM Constants
            // @link http://php.net/manual/en/dom.constants.php
            if ($node->nodeType === XML_TEXT_NODE) {
                // it is probably \n type of text
                continue;
            }

            $childNodes = $node->childNodes;
            $tagName = $node->tagName;

            if (!$childNodes) {
                continue;
            }

            if ($childNodes->length === 0) {
                continue;
            }

            if ($childNodes->length > 1) {
                static::parse($node, $results);
            }

            // nodeText will be DOMText
            // See: http://php.net/manual/en/class.domtext.php
            $nodeText = $childNodes->item(0);

            if (isset($results[$tagName]) && !is_array($results[$tagName])) {
                $results[$tagName] = [$results[$tagName], $nodeText->data];
            } elseif (isset($results[$tagName]) && is_array($results[$tagName])) {
                $results[$tagName][] = $nodeText->data;
            } else {
                $results[$tagName] = $nodeText->data;
            }

            if ($node->attributes && $node->attributes->length > 0) {
                $results["{$tagName}.attributes"] = static::getAttributes($node);
            }
        }
    }

    private function getAttributes($node)
    {
        $attributes = [];
        foreach ($node->attributes as $key => $attribute) {

            // Here Attribute is DOMAttr
            // See: http://php.net/manual/en/class.domattr.php
            $attributes[$attribute->nodeName] = $attribute->value;
        }
        return $attributes;
    }

}