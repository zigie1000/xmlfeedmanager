<?php

class XmlFeedManager extends Module
{
    private $predefinedFeedTypes;
    private $feedTypeFields;

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

        $this->predefinedFeedTypes = array(
            'Products' => 'Products',
            // Add more predefined feed types here
        );

        // Include feed type fields from the external file
        $this->feedTypeFields = include dirname(__FILE__).'/feedTypeFields.php';
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
        $output = null;
        if (Tools::isSubmit('submit'.$this->name)) {
            $feedNames = Tools::getValue('XMLFEEDMANAGER_FEED_NAMES', array());
            $feedUrls = Tools::getValue('XMLFEEDMANAGER_FEED_URLS', array());
            $feedTypes = Tools::getValue('XMLFEEDMANAGER_FEED_TYPES', array());
            $feedSpecificFields = array();

            foreach ($this->feedTypeFields['Products'] as $field) {
                $feedSpecificFields[$field] = Tools::getValue('XMLFEEDMANAGER_'.$field, '');
            }

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

            // Update configuration for feed specific fields
            foreach ($feedSpecificFields as $key => $value) {
                Configuration::updateValue('XMLFEEDMANAGER_'.$key, $value);
            }

            $output .= $this->displayConfirmation($this->l('Settings updated'));
        }
        return $output.$this->renderForm();
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

        // Add predefined feed types selection
        $fields_form['form']['input'][] = array(
            'type' => 'select',
            'label' => $this->l('Predefined Feed Types'),
            'name' => 'XMLFEEDMANAGER_PREDEFINED_FEED_TYPES',
            'options' => array(
                'query' => array_map(function ($key, $value) {
                    return array('id' => $key, 'name' => $value);
                }, array_keys($this->predefinedFeedTypes), $this->predefinedFeedTypes),
                'id' => 'id',
                'name' => 'name'
            ),
            'value' => 'Products' // Default to Products, can be dynamic based on requirement
        );

        // Add specific fields for the selected feed type
        foreach ($this->feedTypeFields['Products'] as $field) {
            $fields_form['form']['input'][] = array(
                'type' => 'text',
                'label' => $this->l(ucwords(str_replace('_', ' ', $field))),
                'name' => 'XMLFEEDMANAGER_'.$field,
                'value' => Configuration::get('XMLFEEDMANAGER_'.$field, ''),
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

        $fieldsValues = array(
            'XMLFEEDMANAGER_FEED_NAMES' => implode("\n", $feedNames),
            'XMLFEEDMANAGER_FEED_URLS' => implode("\n", $feedUrls),
            'XMLFEEDMANAGER_FEED_TYPES' => implode("\n", $feedTypes),
            'XMLFEEDMANAGER_MARKUP_PERCENTAGE' => Configuration::get('XMLFEEDMANAGER_MARKUP_PERCENTAGE', 0),
        );

        // Fetch values for specific fields for the selected feed type
        foreach ($this->feedTypeFields['Products'] as $field) {
            $fieldsValues['XMLFEEDMANAGER_'.$field] = Configuration::get('XMLFEEDMANAGER_'.$field, '');
        }

        return $fieldsValues;
    }

    public function hookActionAdminControllerSetMedia($params)
    {
        $this->context->controller->addJS($this->_path.'views/js/xmlfeedmanager.js');
    }
}
