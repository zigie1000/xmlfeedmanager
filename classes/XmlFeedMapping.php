<?php
class XmlFeedMapping extends ObjectModel
{
    public $id_mapping;
    public $xml_field;
    public $prestashop_field;

    public static $definition = array(
        'table' => 'xmlfeedmanager_mappings',
        'primary' => 'id_mapping',
        'fields' => array(
            'xml_field' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true),
            'prestashop_field' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true),
        ),
    );

    public function __construct($id_mapping = null)
    {
        parent::__construct($id_mapping);
    }

    public static function getAllMappings()
    {
        $sql = 'SELECT * FROM '._DB_PREFIX_.'xmlfeedmanager_mappings';
        return Db::getInstance()->executeS($sql);
    }

    public static function getMappingById($id_mapping)
    {
        $sql = 'SELECT * FROM '._DB_PREFIX_.'xmlfeedmanager_mappings WHERE id_mapping = '.(int)$id_mapping;
        return Db::getInstance()->getRow($sql);
    }

    public static function addMapping($xml_field, $prestashop_field)
    {
        $mapping = new XmlFeedMapping();
        $mapping->xml_field = pSQL($xml_field);
        $mapping->prestashop_field = pSQL($prestashop_field);
        return $mapping->add();
    }

    public static function deleteMapping($id_mapping)
    {
        $mapping = new XmlFeedMapping($id_mapping);
        return $mapping->delete();
    }
}
