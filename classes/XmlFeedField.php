<?php
class XmlFeedField extends ObjectModel
{
    public $id;
    public $feed_type;
    public $field_name;
    public $mapped_field;

    public static $definition = array(
        'table' => 'xmlfeedfield',
        'primary' => 'id',
        'fields' => array(
            'feed_type' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true),
            'field_name' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true),
            'mapped_field' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true),
        ),
    );
}
?>
