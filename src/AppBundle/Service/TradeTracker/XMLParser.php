<?php

namespace AppBundle\Service\TradeTracker;


class XMLParser
{

    /**
     * Parse a XMLNode Recursively and return an array.
     *
     * @param $node
     * @param array $results
     */
    public function parse($node, &$results, array $allowedAttributes = [])
    {
        foreach ($node->childNodes as $index => $node) {

            // for DOM Constants
            // @link http://php.net/manual/en/dom.constants.php
            if ($node->nodeType === XML_TEXT_NODE) {
                // it is probably \n type of text
                continue;
            }

            $childNodes = $node->childNodes;
            $tagName = $node->tagName;

            if (!in_array($tagName, $allowedAttributes)) {
                continue;
            }

            if (!$childNodes) {
                continue;
            }

            if ($childNodes->length === 0) {
                continue;
            }

            if ($childNodes->length > 1) {
                static::parse($node, $results, $allowedAttributes);
            }

            // nodeText will be DOMText
            // See: http://php.net/manual/en/class.domtext.php
            $nodeText = $childNodes->item(0);

            // Add Node Info to result array.
            $this->addNodeInfo($tagName, $nodeText->data, $node, $results);
        }
    }

    /**
     * Get attributes for the tag.
     *
     * @param $node
     * @return array
     */
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

    /**
     * Add Node Information systematically to $results
     *
     * @param $tagName
     * @param $nodeText
     * @param $node
     * @param $results
     */
    private function addNodeInfo($tagName, $nodeText, $node, &$results)
    {
        // If there is no storage for array create one.
        if (!isset($results['attributes'])) {
            $results['attributes'] = [];
        }

        // if the tag is repeated mean there are multiple tags for this element
        // create an array for that
        if (isset($results[$tagName]) && !is_array($results[$tagName])) {
            $results[$tagName] = [$results[$tagName], $nodeText];
        } // Mean we have already created array for repeated tags just need to push nodeText to array
        elseif (isset($results[$tagName]) && is_array($results[$tagName])) {

            $results[$tagName][] = $nodeText;
        } // Add node data with specific key.
        else {
            $results[$tagName] = $nodeText;
        }

        // Check if node has attributes add attributes to its place properly.
        // Where it can be retrieved systematically.
        if ($node->attributes && $node->attributes->length > 0) {
            $index = is_array($results[$tagName]) ? count($results[$tagName]) : 0;
            $key = $index > -1 ? "{$tagName}.{$index}" : $tagName;
            $results['attributes'][$key] = static::getAttributes($node);
        }
    }
}