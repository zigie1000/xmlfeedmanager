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
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('XML Feed Manager');
        $this->description = $this->l('Manage XML feeds for your PrestaShop store.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
    }

    public function install()
    {
        if (!parent::install() || !$this->registerHook('actionAdminControllerSetMedia')) {
            return false;
        }

        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }

        return true;
    }

    public function getContent()
    {
        $output = '';
        if (Tools::isSubmit('submit' . $this->name)) {
            $markupPercentage = Tools::getValue('XMLFEEDMANAGER_MARKUP_PERCENTAGE', 0);
            Configuration::updateValue('XMLFEEDMANAGER_MARKUP_PERCENTAGE', $markupPercentage);
            Configuration::updateValue('XMLFEEDMANAGER_FEED_TYPES', Tools::getValue('XMLFEEDMANAGER_FEED_TYPES', array()));
            $output .= $this->displayConfirmation($this->l('Settings updated'));
        }
        return $output . $this->renderForm();
    }

    protected function renderForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('XML Feed Manager Settings'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Markup Percentage'),
                        'name' => 'XMLFEEDMANAGER_MARKUP_PERCENTAGE',
                        'desc' => $this->l('Markup percentage to be added to the feed prices'),
                        'suffix' => '%',
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Feed Type'),
                        'name' => 'XMLFEEDMANAGER_FEED_TYPES[]',
                        'options' => array(
                            'query' => $this->getPredefinedFeedTypes(),
                            'id' => 'id',
                            'name' => 'name'
                        ),
                        'multiple' => true,
                        'size' => 5,
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right',
                )
            )
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = (int) Configuration::get('PS_LANG_DEFAULT');
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submit' . $this->name;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($fields_form));
    }

    protected function getConfigFormValues()
    {
        return array(
            'XMLFEEDMANAGER_MARKUP_PERCENTAGE' => Configuration::get('XMLFEEDMANAGER_MARKUP_PERCENTAGE', 0),
            'XMLFEEDMANAGER_FEED_TYPES' => Tools::getValue('XMLFEEDMANAGER_FEED_TYPES', Configuration::get('XMLFEEDMANAGER_FEED_TYPES', array())),
        );
    }

    private function getPredefinedFeedTypes()
    {
        $feedTypes = array();
        try {
            foreach (PrestaShopFeedTypes::getTypes() as $type => $name) {
                $feedTypes[] = array(
                    'id' => $type,
                    'name' => $name
                );
            }
        } catch (Exception $e) {
            // Log the error message
            PrestaShopLogger::addLog("Error retrieving feed types: " . $e->getMessage(), 3, null, 'XMLFeedManager', (int) $this->id);
        }
        return $feedTypes;
    }
}
