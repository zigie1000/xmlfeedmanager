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
        $this->setTemplate('configure.tpl');
    }

    public function postProcess()
    {
        if (Tools::isSubmit('submitxmlfeedmanager')) {
            Configuration::updateValue('XMLFEEDMANAGER_SETTING', Tools::getValue('XMLFEEDMANAGER_SETTING'));
        }

        if (Tools::isSubmit('addXmlField')) {
            $field = new XmlFeedField();
            $field->field_name = Tools::getValue('field_name');
            $field->prestashop_field = Tools::getValue('prestashop_field');
            $field->save();
        }

        if (Tools::isSubmit('deleteXmlField')) {
            $field = new XmlFeedField((int)Tools::getValue('id_field'));
            $field->delete();
        }

        if (Tools::isSubmit('scanXmlFeed')) {
            $handler = new XmlFeedHandler();
            $mappings = $handler->scanFeed($_FILES['xml_file']['tmp_name']);
            $this->context->smarty->assign('mappings', $mappings);
            $this->setTemplate('recommend_mappings.tpl');
        }

        if (Tools::isSubmit('confirmMappings')) {
            $confirmedMappings = Tools::getValue('mappings');
            foreach ($confirmedMappings as $xmlField => $prestashopField) {
                XmlFeedMapping::addMapping($xmlField, $prestashopField);
            }
            $this->context->smarty->assign('confirmation', $this->l('Mappings saved successfully.'));
        }
    }
}
