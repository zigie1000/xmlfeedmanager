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
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('XML Feed Manager');
        $this->description = $this->l('Manage XML feeds for your PrestaShop store.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        if (!Configuration::get('XMLFEEDMANAGER_MARKUP_PERCENTAGE')) {
            Configuration::updateValue('XMLFEEDMANAGER_MARKUP_PERCENTAGE', '10');
        }

        if (!Configuration::get('XMLFEEDMANAGER_FEED_TYPES')) {
            Configuration::updateValue('XMLFEEDMANAGER_FEED_TYPES', json_encode(array_keys(PrestaShopFeedTypes::getTypes())));
        }
    }

    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        if (!parent::install() ||
            !$this->registerHook('header') ||
            !Configuration::updateValue('XMLFEEDMANAGER_MARKUP_PERCENTAGE', '10') ||
            !Configuration::updateValue('XMLFEEDMANAGER_FEED_TYPES', json_encode(array_keys(PrestaShopFeedTypes::getTypes())))
        ) {
            return false;
        }

        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall() ||
            !Configuration::deleteByName('XMLFEEDMANAGER_MARKUP_PERCENTAGE') ||
            !Configuration::deleteByName('XMLFEEDMANAGER_FEED_TYPES')
        ) {
            return false;
        }

        return true;
    }

    public function getContent()
    {
        $output = null;

        if (Tools::isSubmit('submit'.$this->name)) {
            $markupPercentage = strval(Tools::getValue('XMLFEEDMANAGER_MARKUP_PERCENTAGE'));
            if (!$markupPercentage || empty($markupPercentage) || !Validate::isGenericName($markupPercentage)) {
                $output .= $this->displayError($this->l('Invalid Configuration value'));
            } else {
                Configuration::updateValue('XMLFEEDMANAGER_MARKUP_PERCENTAGE', $markupPercentage);
                $output .= $this->displayConfirmation($this->l('Settings updated'));
            }
        }

        return $output.$this->displayForm();
    }

    public function displayForm()
    {
        // Get default language
        $defaultLang = (int)Configuration::get('PS_LANG_DEFAULT');

        // Init Fields form array
        $fieldsForm[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Settings'),
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Markup Percentage'),
                    'name' => 'XMLFEEDMANAGER_MARKUP_PERCENTAGE',
                    'size' => 20,
                    'required' => true
                ),
                array(
                    'type' => 'checkbox',
                    'label' => $this->l('Feed Types'),
                    'name' => 'XMLFEEDMANAGER_FEED_TYPES',
                    'values' => array(
                        'query' => PrestaShopFeedTypes::getTypes(),
                        'id' => 'id',
                        'name' => 'name'
                    ),
                    'expand' => array(
                        'default' => 'show',
                        'show' => array('text' => $this->l('Show'), 'icon' => 'plus-sign-alt'),
                        'hide' => array('text' => $this->l('Hide'), 'icon' => 'minus-sign-alt')
                    ),
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            )
        );

        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

        // Language
        $helper->default_form_language = $defaultLang;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;        // false -> remove toolbar
        $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
        $helper->submit_action = 'submit'.$this->name;
        $helper->toolbar_btn = array(
            'save' => array(
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
                    '&token='.Tools::getAdminTokenLite('AdminModules'),
            ),
            'back' => array(
                'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            )
        );

        // Load current value
        $helper->fields_value['XMLFEEDMANAGER_MARKUP_PERCENTAGE'] = Configuration::get('XMLFEEDMANAGER_MARKUP_PERCENTAGE');
        $helper->fields_value['XMLFEEDMANAGER_FEED_TYPES[]'] = json_decode(Configuration::get('XMLFEEDMANAGER_FEED_TYPES'), true);

        return $helper->generateForm(array($fieldsForm));
    }
}
