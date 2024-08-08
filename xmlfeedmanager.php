<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once(dirname(__FILE__) . '/classes/PrestaShopFeedTypes.php');
require_once(dirname(__FILE__) . '/classes/PrestaShopFeedFields.php');
require_once(dirname(__FILE__) . '/classes/XmlFeedField.php');
require_once(dirname(__FILE__) . '/classes/XmlFeedHandler.php');
require_once(dirname(__FILE__) . '/classes/XmlFeedMapping.php');

class xmlfeedmanager extends Module
{
    public function __construct()
    {
        $this->name = 'xmlfeedmanager';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Marco Zagato';
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('XML Feed Manager');
        $this->description = $this->l('Manage XML feeds for your PrestaShop store.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
    }

    public function install()
    {
        return parent::install() &&
            $this->registerHook('actionProductSave') &&
            $this->registerHook('displayBackOfficeHeader') &&
            $this->installDb();
    }

    public function uninstall()
    {
        return parent::uninstall() && $this->uninstallDb();
    }

    private function installDb()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "xmlfeedmanager_mappings` (
            `id_mapping` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `xml_field` VARCHAR(255) NOT NULL,
            `prestashop_field` VARCHAR(255) NOT NULL,
            PRIMARY KEY (`id_mapping`)
        ) ENGINE=" . _MYSQL_ENGINE_ . " DEFAULT CHARSET=utf8;";

        return Db::getInstance()->execute($sql);
    }

    private function uninstallDb()
    {
        $sql = "DROP TABLE IF EXISTS `" . _DB_PREFIX_ . "xmlfeedmanager_mappings`;";

        return Db::getInstance()->execute($sql);
    }

    public function getContent()
    {
        $output = '';
        if (Tools::isSubmit('submitXmlFeedManager')) {
            // Save the selected feed type
            $feedType = Tools::getValue('XMLFEEDMANAGER_FEED_TYPE');
            Configuration::updateValue('XMLFEEDMANAGER_FEED_TYPE', $feedType);

            // Redirect to mapping configuration page
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminXmlFeedMapping'));
        }

        // Render the configuration form
        $output .= $this->renderForm();
        return $output;
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
                        'type' => 'select',
                        'label' => $this->l('Feed Type'),
                        'name' => 'XMLFEEDMANAGER_FEED_TYPE',
                        'options' => array(
                            'query' => array(
                                array('id' => 'product', 'name' => 'Product Feed'),
                                array('id' => 'category', 'name' => 'Category Feed')
                            ),
                            'id' => 'id',
                            'name' => 'name',
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right'
                ),
            ),
        );

        $helper = new HelperForm();
        $helper->submit_action = 'submitXmlFeedManager';
        $helper->fields_value['XMLFEEDMANAGER_FEED_TYPE'] = Configuration::get('XMLFEEDMANAGER_FEED_TYPE');

        return $helper->generateForm(array($fields_form));
    }
}
