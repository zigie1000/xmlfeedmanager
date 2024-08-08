<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

require_once(dirname(__FILE__) . '/classes/PrestaShopFeedTypes.php');
require_once(dirname(__FILE__) . '/classes/ProductFeedFields.php');

class xmlfeedmanager extends Module
{
    public function __construct()
    {
        $this->name = 'xmlfeedmanager';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Marco Zagato';
        $this->need_instance = 0;
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->l('XML Feed Manager');
        $this->description = $this->l('Manage multiple XML feeds for importing and updating product data without overwriting existing product details.');
    }

    public function install()
    {
        if (!parent::install() || !$this->installDb()) {
            return false;
        }

        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall() || !$this->uninstallDb()) {
            return false;
        }

        return true;
    }

    private function installDb()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'xmlfeedmanager` (
            `id_xmlfeedmanager` int(11) NOT NULL AUTO_INCREMENT,
            `feed_name` varchar(255) NOT NULL,
            `feed_url` varchar(255) NOT NULL,
            `feed_type` varchar(255) NOT NULL,
            `markup_percentage` decimal(5,2) DEFAULT NULL,
            PRIMARY KEY (`id_xmlfeedmanager`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

        return Db::getInstance()->execute($sql);
    }

    private function uninstallDb()
    {
        $sql = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'xmlfeedmanager`;';

        return Db::getInstance()->execute($sql);
    }

    public function getContent()
    {
        if (Tools::isSubmit('submit_xmlfeedmanager')) {
            $this->postProcess();
        }

        return $this->renderForm();
    }

    protected function renderForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('Feed Names (one per line)'),
                        'name' => 'XMLFEEDMANAGER_FEED_NAMES',
                        'cols' => 60,
                        'rows' => 10,
                        'hint' => $this->l('Enter the names of the feeds, each on a new line.'),
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('Feed URLs (one per line)'),
                        'name' => 'XMLFEEDMANAGER_FEED_URLS',
                        'cols' => 60,
                        'rows' => 10,
                        'hint' => $this->l('Enter the URLs of the feeds, each on a new line.'),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Feed Type'),
                        'name' => 'XMLFEEDMANAGER_FEED_TYPES',
                        'options' => array(
                            'query' => $this->getPredefinedFeedTypes(),
                            'id' => 'id',
                            'name' => 'name',
                        ),
                        'multiple' => true,
                        'size' => 5,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Markup Percentage'),
                        'name' => 'XMLFEEDMANAGER_MARKUP_PERCENTAGE',
                        'suffix' => '%',
                        'hint' => $this->l('Enter the markup percentage for the products.'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right',
                ),
            ),
        );

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;
        $helper->toolbar_scroll = true;
        $helper->submit_action = 'submit_xmlfeedmanager';

        $helper->fields_value['XMLFEEDMANAGER_FEED_NAMES'] = Configuration::get('XMLFEEDMANAGER_FEED_NAMES');
        $helper->fields_value['XMLFEEDMANAGER_FEED_URLS'] = Configuration::get('XMLFEEDMANAGER_FEED_URLS');
        $helper->fields_value['XMLFEEDMANAGER_FEED_TYPES'] = Configuration::get('XMLFEEDMANAGER_FEED_TYPES');
        $helper->fields_value['XMLFEEDMANAGER_MARKUP_PERCENTAGE'] = Configuration::get('XMLFEEDMANAGER_MARKUP_PERCENTAGE');

        return $helper->generateForm(array($fields_form));
    }

    private function getPredefinedFeedTypes()
    {
        $feedTypes = array();
        foreach (PrestaShopFeedTypes::getTypes() as $type => $name) {
            $feedTypes[] = array(
                'id' => $type,
                'name' => $name
            );
        }
        return $feedTypes;
    }

    private function postProcess()
    {
        try {
            Configuration::updateValue('XMLFEEDMANAGER_FEED_NAMES', Tools::getValue('XMLFEEDMANAGER_FEED_NAMES'));
            Configuration::updateValue('XMLFEEDMANAGER_FEED_URLS', Tools::getValue('XMLFEEDMANAGER_FEED_URLS'));
            Configuration::updateValue('XMLFEEDMANAGER_FEED_TYPES', Tools::getValue('XMLFEEDMANAGER_FEED_TYPES'));
            Configuration::updateValue('XMLFEEDMANAGER_MARKUP_PERCENTAGE', Tools::getValue('XMLFEEDMANAGER_MARKUP_PERCENTAGE'));
            $this->context->controller->confirmations[] = $this->l('Settings updated');
        } catch (Exception $e) {
            $this->context->controller->errors[] = $this->l('An error occurred while updating settings: ') . $e->getMessage();
        }
    }
}
?>
