<?php

class AdminXmlFeedManagerController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        parent::__construct();
    }

    public function initContent()
    {
        parent::initContent();
        $this->context->smarty->assign([
            'mappings' => XmlFeedMapping::getMappings(),
            'fields' => PrestaShopFeedFields::getFields(),
        ]);
        $this->setTemplate('mapping.tpl');
    }

    public function postProcess()
    {
        if (Tools::isSubmit('submitSave')) {
            $xmlField = Tools::getValue('xml_field');
            $prestashopField = Tools::getValue('prestashop_field');
            if ($xmlField && $prestashopField) {
                XmlFeedMapping::saveMapping($xmlField, $prestashopField);
            }
        } elseif (Tools::isSubmit('submitDelete')) {
            $idMapping = Tools::getValue('id_mapping');
            if ($idMapping) {
                XmlFeedMapping::deleteMapping($idMapping);
            }
        }
        parent::postProcess();
    }
}
