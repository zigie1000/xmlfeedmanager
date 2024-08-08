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
        $this->context->smarty->assign(array(
            'module_dir' => $this->module->getPathUri(),
        ));
        $this->setTemplate('admin/configure.tpl');
    }

    public function postProcess()
    {
        if (Tools::isSubmit('submitXMLFeedManager')) {
            Configuration::updateValue('XMLFEEDMANAGER_FEED_NAME', Tools::getValue('XMLFEEDMANAGER_FEED_NAME'));
            Configuration::updateValue('XMLFEEDMANAGER_FEED_URL', Tools::getValue('XMLFEEDMANAGER_FEED_URL'));
            Configuration::updateValue('XMLFEEDMANAGER_FEED_TYPE', Tools::getValue('XMLFEEDMANAGER_FEED_TYPE'));
            Configuration::updateValue('XMLFEEDMANAGER_MAPPING', Tools::getValue('XMLFEEDMANAGER_MAPPING'));
        }
    }
}
