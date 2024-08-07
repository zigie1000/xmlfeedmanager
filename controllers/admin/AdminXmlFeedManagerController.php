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
        $this->context->smarty->assign(array(
            'module_name' => $this->module->name,
            'current' => $this->context->link->getAdminLink('AdminModules', true),
            'token' => Tools::getAdminTokenLite('AdminModules'),
            'XMLFEEDMANAGER_FEED_NAMES' => Configuration::get('XMLFEEDMANAGER_FEED_NAMES'),
            'XMLFEEDMANAGER_FEED_URLS' => Configuration::get('XMLFEEDMANAGER_FEED_URLS'),
            'XMLFEEDMANAGER_FEED_TYPES' => Configuration::get('XMLFEEDMANAGER_FEED_TYPES'),
            'XMLFEEDMANAGER_MARKUP_PERCENTAGE' => Configuration::get('XMLFEEDMANAGER_MARKUP_PERCENTAGE'),
            'submit_text' => $this->l('Save'),
        ));

        parent::initContent();
        $this->setTemplate('configure.tpl');
    }

    public function postProcess()
    {
        if (Tools::isSubmit('submitSave')) {
            Configuration::updateValue('XMLFEEDMANAGER_FEED_NAMES', Tools::getValue('XMLFEEDMANAGER_FEED_NAMES'));
            Configuration::updateValue('XMLFEEDMANAGER_FEED_URLS', Tools::getValue('XMLFEEDMANAGER_FEED_URLS'));
            Configuration::updateValue('XMLFEEDMANAGER_FEED_TYPES', Tools::getValue('XMLFEEDMANAGER_FEED_TYPES'));
            Configuration::updateValue('XMLFEEDMANAGER_MARKUP_PERCENTAGE', Tools::getValue('XMLFEEDMANAGER_MARKUP_PERCENTAGE'));
        }
    }
}
