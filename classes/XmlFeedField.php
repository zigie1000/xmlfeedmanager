<?php
class XmlFeedField extends ObjectModel
{
    public $id_field;
    public $field_name;
    public $prestashop_field;

    public static $definition = array(
        'table' => 'xmlfeedmanager_fields',
        'primary' => 'id_field',
        'fields' => array(
            'field_name' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true, 'size' => 255),
            'prestashop_field' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true, 'size' => 255),
        ),
    );

    public function __construct($id_field = null)
    {
        parent::__construct($id_field);
    }
}
