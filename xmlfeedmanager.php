<?php

class XmlFeedManager extends Module
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
        $this->description = $this->l('Manage multiple XML feeds for importing and updating product data without overwriting existing products.');
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        return parent::install() && $this->registerHook('actionAdminControllerSetMedia') && $this->installDb();
    }

    public function uninstall()
    {
        return parent::uninstall() && $this->uninstallDb();
    }

    private function installDb()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'xmlfeedmanager_feeds` (
            `id_feed` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `feed_name` VARCHAR(255) NOT NULL,
            `feed_url` TEXT NOT NULL,
            `feed_type` ENUM("full", "update") NOT NULL,
            `last_imported` DATETIME,
            PRIMARY KEY (`id_feed`)
        ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';
        return Db::getInstance()->execute($sql);
    }

    private function uninstallDb()
    {
        $sql = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'xmlfeedmanager_feeds`;';
        return Db::getInstance()->execute($sql);
    }

    public function getContent()
    {
        ob_start(); // Start output buffering to avoid "headers already sent" error
        $output = '';
        if (Tools::isSubmit('submit'.$this->name)) {
            $feedNames = Tools::getValue('XMLFEEDMANAGER_FEED_NAMES');
            $feedUrls = Tools::getValue('XMLFEEDMANAGER_FEED_URLS');
            $feedTypes = Tools::getValue('XMLFEEDMANAGER_FEED_TYPES');
            Db::getInstance()->execute('TRUNCATE TABLE '._DB_PREFIX_.'xmlfeedmanager_feeds');
            foreach ($feedNames as $index => $feedName) {
                if (!empty($feedName) && !empty($feedUrls[$index])) {
                    Db::getInstance()->insert('xmlfeedmanager_feeds', array(
                        'feed_name' => pSQL($feedName),
                        'feed_url' => pSQL($feedUrls[$index]),
                        'feed_type' => pSQL($feedTypes[$index]),
                        'last_imported' => null
                    ));
                }
            }
            $markupPercentage = Tools::getValue('XMLFEEDMANAGER_MARKUP_PERCENTAGE', 0);
            Configuration::updateValue('XMLFEEDMANAGER_MARKUP_PERCENTAGE', $markupPercentage);
            $output .= $this->displayConfirmation($this->l('Settings updated'));
        }
        $output .= $this->renderForm();
        ob_end_flush(); // End output buffering and flush the output
        return $output;
    }

    protected function renderForm()
    {
        $feeds = Db::getInstance()->executeS('SELECT * FROM '._DB_PREFIX_.'xmlfeedmanager_feeds');
        $feedNames = array();
        $feedUrls = array();
        $feedTypes = array();
        foreach ($feeds as $feed) {
            $feedNames[] = $feed['feed_name'];
            $feedUrls[] = $feed['feed_url'];
            $feedTypes[] = $feed['feed_type'];
        }
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                ),
                'input' => array(
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('Feed Names (one per line)'),
                        'name' => 'XMLFEEDMANAGER_FEED_NAMES',
                        'cols' => 60,
                        'rows' => 10,
                        'value' => implode("\n", $feedNames),
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('Feed URLs (one per line)'),
                        'name' => 'XMLFEEDMANAGER_FEED_URLS',
                        'cols' => 60,
                        'rows' => 10,
                        'value' => implode("\n", $feedUrls),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Markup Percentage'),
                        'name' => 'XMLFEEDMANAGER_MARKUP_PERCENTAGE',
                        'value' => Configuration::get('XMLFEEDMANAGER_MARKUP_PERCENTAGE', 0),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right'
                )
            )
        );
        foreach ($feeds as $index => $feed) {
            $fields_form['form']['input'][] = array(
                'type' => 'select',
                'label' => $this->l('Feed Type'),
                'name' => 'XMLFEEDMANAGER_FEED_TYPES[]',
                'options' => array(
                    'query' => array(
                        array('id' => 'full', 'name' => $this->l('Full')),
                        array('id' => 'update', 'name' => $this->l('Update'))
                    ),
                    'id' => 'id',
                    'name' => 'name'
                ),
                'value' => $feed['feed_type']
            );
        }
        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->title = $this->displayName;
        $helper->submit_action = 'submit'.$this->name;
        $helper->fields_value = $this->getConfigFieldsValues($feeds);
        return $helper->generateForm(array($fields_form));
    }

    public function getConfigFieldsValues($feeds)
    {
        $feedNames = array();
        $feedUrls = array();
        $feedTypes = array();
        foreach ($feeds as $feed) {
            $feedNames[] = $feed['feed_name'];
            $feedUrls[] = $feed['feed_url'];
            $feedTypes[] = $feed['feed_type'];
        }
        return array(
            'XMLFEEDMANAGER_FEED_NAMES' => implode("\n", $feedNames),
            'XMLFEEDMANAGER_FEED_URLS' => implode("\n", $feedUrls),
            'XMLFEEDMANAGER_MARKUP_PERCENTAGE' => Configuration::get('XMLFEEDMANAGER_MARKUP_PERCENTAGE', 0),
            'XMLFEEDMANAGER_FEED_TYPES' => $feedTypes,
        );
    }

    public function hookActionAdminControllerSetMedia($params)
    {
        $this->context->controller->addJS($this->_path.'views/js/xmlfeedmanager.js');
    }
}
