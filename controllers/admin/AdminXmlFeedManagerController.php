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
        $this->content = $this->module->getContent();
        parent::initContent();
    }
}
