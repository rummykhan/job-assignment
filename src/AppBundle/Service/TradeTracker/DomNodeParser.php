<?php

namespace AppBundle\Service\TradeTracker;


class DomNodeParser
{
    /**
     * @param $node
     * @param array $arr
     * @return array
     *
     *
     *
     */
    public function parse($node, $arr = [])
    {
        $results = $arr;

        if ($node->childNodes) {


            foreach ($node->childNodes as $node) {

                if ($node->nodeType === XML_TEXT_NODE) {
                    // it is probably \n type of text
                    continue;
                }

                if ($node->nodeType === XML_ELEMENT_NODE) {

                    if ($node->childNodes && $node->childNodes->length === 1) {

                        echo "if: " . $node->tagName;
                        echo '<br>';

                        // nodeText will be DOMText
                        // See: http://php.net/manual/en/class.domtext.php
                        $results[$node->tagName] = $node->childNodes->item(0)->data;

                        if ($node->attributes && $node->attributes->length > 0) {
                            $results["@{$node->tagName}"] = self::getAttributes($node);
                        }
                    } else {
                        echo "else:" . $node->tagName;
                        echo '<br>';
                        self::parse($node, $results);
                    }
                }
            }
        }

        return $results;
    }

    private
    function getAttributes($node)
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