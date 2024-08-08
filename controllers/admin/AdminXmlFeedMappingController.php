<?php

class AdminXmlFeedMappingController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'xmlfeedmanager_mappings';
        $this->className = 'XmlFeedMapping';
        $this->fields_list = array(
            'id_mapping' => array(
                'title' => $this->l('ID'),
                'align' => 'center',
                'width' => 25
            ),
            'xml_field' => array(
                'title' => $this->l('XML Field'),
                'width' => 'auto'
            ),
            'prestashop_field' => array(
                'title' => $this->l('PrestaShop Field'),
                'width' => 'auto'
            ),
        );
        parent::__construct();
    }

    public function renderForm()
    {
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('XML Feed Mapping'),
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('XML Field'),
                    'name' => 'xml_field',
                    'required' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('PrestaShop Field'),
                    'name' => 'prestashop_field',
                    'required' => true,
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
            )
        );

        return parent::renderForm();
    }

    public function postProcess()
    {
        if (Tools::isSubmit('submitAdd' . $this->table)) {
            $xmlField = Tools::getValue('xml_field');
            $prestashopField = Tools::getValue('prestashop_field');

            if (!$xmlField || !$prestashopField) {
                $this->errors[] = $this->l('Both fields are required.');
            } else {
                $mapping = new XmlFeedMapping();
                $mapping->xml_field = $xmlField;
                $mapping->prestashop_field = $prestashopField;

                if (!$mapping->save()) {
                    $this->errors[] = $this->l('Failed to save mapping.');
                } else {
                    $this->confirmations[] = $this->l('Mapping saved successfully.');
                }
            }
        } elseif (Tools::isSubmit('delete' . $this->table)) {
            $idMapping = Tools::getValue('id_mapping');
            $mapping = new XmlFeedMapping($idMapping);

            if (!$mapping->delete()) {
                $this->errors[] = $this->l('Failed to delete mapping.');
            } else {
                $this->confirmations[] = $this->l('Mapping deleted successfully.');
            }
        }

        parent::postProcess();
    }
}
