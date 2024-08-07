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
        // Include the CSS file for the module
        $this->context->controller->addCSS($this->module->getPathUri() . 'views/css/xmlfeedmanager.css');
        // Include the JavaScript file for the module
        $this->context->controller->addJS($this->module->getPathUri() . 'views/js/xmlfeedmanager.js');
        
        parent::initContent();
        // Set the template for the configuration page
        $this->setTemplate('configure.tpl');
    }
}
?>
