<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

require_once(dirname(__FILE__) . '/classes/PrestaShopFeedTypes.php');
require_once(dirname(__FILE__) . '/classes/PrestaShopFeedFields.php');
require_once(dirname(__FILE__) . '/classes/XmlFeedHandler.php');

class XMLFeedManager extends Module
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
        $this->description = $this->l('Manage XML feeds for your Prestashop store.');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        if (!Configuration::get('XMLFEEDMANAGER_FEED_TYPES')) {
            $this->warning = $this->l('No feed types provided.');
        }
    }

    public function install()
    {
        return parent::install() &&
            $this->registerHook('actionAdminControllerSetMedia') &&
            Configuration::updateValue('XMLFEEDMANAGER_FEED_TYPES', json_encode([]));
    }

    public function uninstall()
    {
        return parent::uninstall() &&
            Configuration::deleteByName('XMLFEEDMANAGER_FEED_TYPES');
    }

    public function getContent()
    {
        $output = null;

        if (Tools::isSubmit('submit' . $this->name)) {
            $feedTypes = Tools::getValue('XMLFEEDMANAGER_FEED_TYPES');
            if (!$feedTypes || empty($feedTypes)) {
                $output .= $this->displayError($this->l('Invalid Configuration value'));
            } else {
                Configuration::updateValue('XMLFEEDMANAGER_FEED_TYPES', json_encode($feedTypes));
                $output .= $this->displayConfirmation($this->l('Settings updated'));
            }
        }

        return $output . $this->renderForm();
    }

    public function renderForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
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
                        'required' => true,
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
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->title = $this->displayName;
        $helper->submit_action = 'submit' . $this->name;
        $helper->fields_value['XMLFEEDMANAGER_FEED_TYPES'] = json_decode(Configuration::get('XMLFEEDMANAGER_FEED_TYPES'), true);

        return $helper->generateForm(array($fields_form));
    }

    public function hookActionAdminControllerSetMedia()
    {
        $this->context->controller->addJS($this->_path . 'views/js/xmlfeedmanager.js');
        $this->context->controller->addCSS($this->_path . 'views/css/xmlfeedmanager.css');
    }
}
?>
