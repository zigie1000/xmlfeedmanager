<?php
class AdminXmlFeedManagerController extends ModuleAdminController
{
    public function __construct()
    {
        $this->name = 'xmlfeedmanager';
        $this->bootstrap = true;
        parent::__construct();
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
                        'type' => 'textarea',
                        'label' => $this->l('Feed Names (one per line)'),
                        'name' => 'XMLFEEDMANAGER_FEED_NAMES',
                        'rows' => 3,
                        'cols' => 40,
                        'hint' => $this->l('Enter feed names, one per line.')
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('Feed URLs (one per line)'),
                        'name' => 'XMLFEEDMANAGER_FEED_URLS',
                        'rows' => 3,
                        'cols' => 40,
                        'hint' => $this->l('Enter feed URLs, one per line.')
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('Feed Types (one per line)'),
                        'name' => 'XMLFEEDMANAGER_FEED_TYPES',
                        'rows' => 3,
                        'cols' => 40,
                        'hint' => $this->l('Enter feed types, one per line (e.g., full, update).')
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Markup Percentage'),
                        'name' => 'XMLFEEDMANAGER_MARKUP_PERCENTAGE',
                        'suffix' => '%',
                        'hint' => $this->l('Enter the markup percentage.')
                    )
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right'
                )
            )
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this->module;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submit' . $this->name;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->module->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($fields_form));
    }

    public function getConfigFieldsValues()
    {
        return array(
            'XMLFEEDMANAGER_FEED_NAMES' => Tools::getValue('XMLFEEDMANAGER_FEED_NAMES', Configuration::get('XMLFEEDMANAGER_FEED_NAMES')),
            'XMLFEEDMANAGER_FEED_URLS' => Tools::getValue('XMLFEEDMANAGER_FEED_URLS', Configuration::get('XMLFEEDMANAGER_FEED_URLS')),
            'XMLFEEDMANAGER_FEED_TYPES' => Tools::getValue('XMLFEEDMANAGER_FEED_TYPES', Configuration::get('XMLFEEDMANAGER_FEED_TYPES')),
            'XMLFEEDMANAGER_MARKUP_PERCENTAGE' => Tools::getValue('XMLFEEDMANAGER_MARKUP_PERCENTAGE', Configuration::get('XMLFEEDMANAGER_MARKUP_PERCENTAGE'))
        );
    }

    public function postProcess()
    {
        if (Tools::isSubmit('submit' . $this->name)) {
            Configuration::updateValue('XMLFEEDMANAGER_FEED_NAMES', Tools::getValue('XMLFEEDMANAGER_FEED_NAMES'));
            Configuration::updateValue('XMLFEEDMANAGER_FEED_URLS', Tools::getValue('XMLFEEDMANAGER_FEED_URLS'));
            Configuration::updateValue('XMLFEEDMANAGER_FEED_TYPES', Tools::getValue('XMLFEEDMANAGER_FEED_TYPES'));
            Configuration::updateValue('XMLFEEDMANAGER_MARKUP_PERCENTAGE', Tools::getValue('XMLFEEDMANAGER_MARKUP_PERCENTAGE'));
        }
    }
}
?>
