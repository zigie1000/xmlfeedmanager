<?php
class XmlFeedMapping
{
    public $xml_field;
    public $prestashop_field;

    public function __construct($xml_field, $prestashop_field)
    {
        $this->xml_field = $xml_field;
        $this->prestashop_field = $prestashop_field;
    }
}
