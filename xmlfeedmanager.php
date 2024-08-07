<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

require_once dirname(__FILE__).'/classes/PrestaShopFeedTypes.php';

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
                    `feed_type` ENUM("product", "category", "manufacturer", "supplier", "combination", "attribute", "attribute_group", "feature", "feature_value", "customer", "address", "order", "order_detail", "order_state", "tax_rules", "zone", "currency", "country", "state", "warehouse", "stock_movement", "supply_order", "carrier", "shop", "shop_group", "cms", "cms_category", "store", "contact", "meta", "tax", "attachment", "cart_rule", "specific_price_rule", "specific_price", "image", "customization", "customization_field", "group", "alias", "tag", "attribute_lang", "attribute_group_lang", "feature_lang", "feature_value_lang", "supplier_lang", "manufacturer_lang", "product_lang", "category_lang", "cms_lang", "cms_category_lang", "store_lang", "tag_lang", "carrier_lang", "meta_lang", "stock_available", "shop_url", "order_invoice", "order_invoice_detail", "order_message", "order_message_lang", "order_return", "order_return_detail", "order_slip", "order_slip_detail", "order_payment") NOT NULL,
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
            try {
                $feedNames = explode("\n", Tools::getValue('XMLFEEDMANAGER_FEED_NAMES'));
                $feedUrls = explode("\n", Tools::getValue('XMLFEEDMANAGER_FEED_URLS'));
                $feedTypes = Tools::getValue('XMLFEEDMANAGER_PREDEFINED_FEED_TYPES');

                if (count($feedNames) !== count($feedUrls) || count($feedNames) !== count($feedTypes)) {
                    throw new Exception($this->l('Feed Names, URLs, and Types count mismatch.'));
                }

                Db::getInstance()->execute('TRUNCATE TABLE '._DB_PREFIX_.'xmlfeedmanager_feeds');
                foreach ($feedNames as $index => $feedName) {
                    if (!empty($feedName) && !empty($feedUrls[$index]) && !empty($feedTypes[$index])) {
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
            } catch (Exception $e) {
                $output .= $this->displayError($this->l('An error occurred: ').$e->getMessage());
            }
        }
        return $output.$this->renderForm();
    }

    protected function renderForm()
    {
        try {
            $feeds = Db::getInstance()->executeS('SELECT * FROM '._DB_PREFIX_.'xmlfeedmanager_feeds');
            $feedNames = array();
            $feedUrls = array();
            $feedTypes = array();

            foreach ($feeds as $feed) {
                $feedNames[] = $feed['feed_name'];
                $feedUrls[] = $feed['feed_url'];
                $feedTypes[] = $feed['feed_type'];
            }

            $predefinedFeedTypes = PrestaShopFeedTypes::getTypes();

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
                            'type' => 'select',
                            'label' => $this->l('Predefined Feed Types'),
                            'name' => 'XMLFEEDMANAGER_PREDEFINED_FEED_TYPES[]',
                            'multiple' => true,
                            'options' => array(
                                'query' => array_map(function($key, $value) {
                                    return array('id' => $key, 'name' => $value);
                                }, array_keys($predefinedFeedTypes), $predefinedFeedTypes),
                                'id' => 'id',
                                'name' => 'name'
                            ),
                            'value' => $feedTypes
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
        } catch (Exception $e) {
            return $this->displayError($this->l('An error occurred while rendering the form: ').$e->getMessage());
        }
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
            'XMLFEEDMANAGER_PREDEFINED_FEED_TYPES' => $feedTypes,
        );
    }

    public function hookActionAdminControllerSetMedia($params)
    {
        $this->context->controller->addJS($this->_path.'views/js/xmlfeedmanager.js');
        $this->context->controller->addCSS($this->_path.'views/css/xmlfeedmanager.css');
    }
}            
