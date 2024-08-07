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
        $this->author_uri = 'https://smartmail.store';
        $this->need_instance = 0;

        parent::__construct();

        $this->displayName = $this->l('XML Feed Manager');
        $this->description = $this->l('Manage XML feeds for your shop.');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        return parent::install() &&
            $this->registerHook('header') &&
            Configuration::updateValue('XMLFEEDMANAGER_FEED_NAMES', '') &&
            Configuration::updateValue('XMLFEEDMANAGER_FEED_URLS', '') &&
            Configuration::updateValue('XMLFEEDMANAGER_FEED_TYPES', '') &&
            Configuration::updateValue('XMLFEEDMANAGER_MARKUP_PERCENTAGE', '0');
    }

    public function uninstall()
    {
        return parent::uninstall() &&
            Configuration::deleteByName('XMLFEEDMANAGER_FEED_NAMES') &&
            Configuration::deleteByName('XMLFEEDMANAGER_FEED_URLS') &&
            Configuration::deleteByName('XMLFEEDMANAGER_FEED_TYPES') &&
            Configuration::deleteByName('XMLFEEDMANAGER_MARKUP_PERCENTAGE');
    }

    public function getContent()
    {
        $output = null;

        if (Tools::isSubmit('submit' . $this->name)) {
            Configuration::updateValue('XMLFEEDMANAGER_FEED_NAMES', Tools::getValue('XMLFEEDMANAGER_FEED_NAMES'));
            Configuration::updateValue('XMLFEEDMANAGER_FEED_URLS', Tools::getValue('XMLFEEDMANAGER_FEED_URLS'));
            Configuration::updateValue('XMLFEEDMANAGER_FEED_TYPES', Tools::getValue('XMLFEEDMANAGER_FEED_TYPES'));
            Configuration::updateValue('XMLFEEDMANAGER_MARKUP_PERCENTAGE', Tools::getValue('XMLFEEDMANAGER_MARKUP_PERCENTAGE'));

            $output .= $this->displayConfirmation($this->l('Settings updated'));
        }

        return $output . $this->renderForm();
    }

    public function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submit' . $this->name;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    public function getConfigForm()
    {
        return array(
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
                        'rows' => 5,
                        'cols' => 50,
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('Feed URLs (one per line)'),
                        'name' => 'XMLFEEDMANAGER_FEED_URLS',
                        'rows' => 5,
                        'cols' => 50,
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('Feed Types (one per line)'),
                        'name' => 'XMLFEEDMANAGER_FEED_TYPES',
                        'rows' => 5,
                        'cols' => 50,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Markup Percentage'),
                        'name' => 'XMLFEEDMANAGER_MARKUP_PERCENTAGE',
                        'suffix' => '%',
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    public function getConfigFieldsValues()
    {
        return array(
            'XMLFEEDMANAGER_FEED_NAMES' => Tools::getValue('XMLFEEDMANAGER_FEED_NAMES', Configuration::get('XMLFEEDMANAGER_FEED_NAMES')),
            'XMLFEEDMANAGER_FEED_URLS' => Tools::getValue('XMLFEEDMANAGER_FEED_URLS', Configuration::get('XMLFEEDMANAGER_FEED_URLS')),
            'XMLFEEDMANAGER_FEED_TYPES' => Tools::getValue('XMLFEEDMANAGER_FEED_TYPES', Configuration::get('XMLFEEDMANAGER_FEED_TYPES')),
            'XMLFEEDMANAGER_MARKUP_PERCENTAGE' => Tools::getValue('XMLFEEDMANAGER_MARKUP_PERCENTAGE', Configuration::get('XMLFEEDMANAGER_MARKUP_PERCENTAGE')),
        );
    }

    public function hookHeader($params)
    {
        $this->context->controller->addCSS($this->_path . 'views/css/xmlfeedmanager.css', 'all');
    }
}
