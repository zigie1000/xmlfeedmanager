<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class XmlFeedManager extends Module
{
    public function __construct()
    {
        $this->name = 'xmlfeedmanager';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Marco Zagato';
        $this->author_uri = 'https://dealbrut.com';
        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('XML Feed Manager');
        $this->description = $this->l('Manage XML feeds for importing and exporting product data.');
    }

    public function install()
    {
        return parent::install() &&
               $this->registerHook('actionAdminControllerSetMedia') &&
               $this->installDb();
    }

    public function uninstall()
    {
        return parent::uninstall() && $this->uninstallDb();
    }

    private function installDb()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."xmlfeedmanager_fields` (
            `id_field` INT(11) NOT NULL AUTO_INCREMENT,
            `field_name` VARCHAR(255) NOT NULL,
            `prestashop_field` VARCHAR(255) NOT NULL,
            PRIMARY KEY (`id_field`)
        ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8;";
        
        return Db::getInstance()->execute($sql);
    }

    private function uninstallDb()
    {
        $sql = "DROP TABLE IF EXISTS `"._DB_PREFIX_."xmlfeedmanager_fields`;";
        return Db::getInstance()->execute($sql);
    }

    public function getContent()
    {
        $output = null;

        if (Tools::isSubmit('submit' . $this->name)) {
            Configuration::updateValue('XMLFEEDMANAGER_SETTING', Tools::getValue('XMLFEEDMANAGER_SETTING'));
            $output .= $this->displayConfirmation($this->l('Settings updated'));
        }

        return $output . $this->renderForm();
    }

    protected function renderForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Example Setting'),
                        'name' => 'XMLFEEDMANAGER_SETTING',
                        'size' => 20,
                        'required' => true,
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right'
                )
            )
        );

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->title = $this->displayName;
        $helper->submit_action = 'submit' . $this->name;
        $helper->fields_value = $this->getConfigFieldsValues();

        return $helper->generateForm(array($fields_form));
    }

    public function getConfigFieldsValues()
    {
        return array(
            'XMLFEEDMANAGER_SETTING' => Tools::getValue('XMLFEEDMANAGER_SETTING', Configuration::get('XMLFEEDMANAGER_SETTING')),
        );
    }
}
