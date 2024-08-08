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
            $this->registerHook('actionAdminControllerSetMedia') &&
            $this->registerHook('displayBackOfficeHeader');
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    public function getContent()
    {
        $output = null;

        if (Tools::isSubmit('submit'.$this->name)) {
            $feedTypes = strval(Tools::getValue('XMLFEEDMANAGER_FEED_TYPES'));
            if (!$feedTypes || empty($feedTypes) || !Validate::isGenericName($feedTypes)) {
                $output .= $this->displayError($this->l('Invalid Configuration value'));
            } else {
                Configuration::updateValue('XMLFEEDMANAGER_FEED_TYPES', $feedTypes);
                $output .= $this->displayConfirmation($this->l('Settings updated'));
            }
        }

        return $output.$this->renderForm();
    }

    public function renderForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'select',
                        'label' => $this->l('Feed Type'),
                        'name' => 'XMLFEEDMANAGER_FEED_TYPES[]',
                        'options' => array(
                            'query' => PrestaShopFeedTypes::getTypes(),
                            'id' => 'id',
                            'name' => 'name'
                        ),
                        'multiple' => true,
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right'
                )
            ),
        );

        $helper = new HelperForm();

        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->title = $this->displayName;
        $helper->show_toolbar = true;
        $helper->toolbar_scroll = true;
        $helper->submit_action = 'submit'.$this->name;
        $helper->fields_value['XMLFEEDMANAGER_FEED_TYPES[]'] = Configuration::get('XMLFEEDMANAGER_FEED_TYPES');

        return $helper->generateForm(array($fields_form));
    }

    public function hookActionAdminControllerSetMedia($params)
    {
        $this->context->controller->addJS($this->_path.'views/js/xmlfeedmanager.js');
        $this->context->controller->addCSS($this->_path.'views/css/xmlfeedmanager.css');
    }

    public function hookDisplayBackOfficeHeader($params)
    {
        $this->context->controller->addCSS($this->_path.'views/css/xmlfeedmanager.css');
    }
}
