<?php
class XmlFeedField
{
    public $id;
    public $name;
    public $value;

    public function __construct($id, $name, $value)
    {
        $this->id = $id;
        $this->name = $name;
        $this->value = $value;
    }
}
