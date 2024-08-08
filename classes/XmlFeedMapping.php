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
            'xml_field' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true, 'size' => 255),
            'prestashop_field' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true, 'size' => 255),
        ),
    );

    public static function getMappings()
    {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('xmlfeedmanager_mappings');

        return Db::getInstance()->executeS($sql);
    }

    public static function saveMapping($xmlField, $prestashopField)
    {
        $mapping = new self();
        $mapping->xml_field = $xmlField;
        $mapping->prestashop_field = $prestashopField;

        return $mapping->save();
    }

    public static function deleteMapping($idMapping)
    {
        $mapping = new self($idMapping);
        return $mapping->delete();
    }
}
