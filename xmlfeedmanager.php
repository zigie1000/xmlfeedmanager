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
        $this->version = '1.1.0';
        $this->author = 'Marco Zagato';
        $this->author_uri = 'https://dealbrut.com';
        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('XML Feed Manager');
        $this->description = $this->l('Manage multiple XML feeds for importing and updating product data without overwriting existing products.');
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
        $sql1 = "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."xmlfeedmanager_feeds` (
            `id_feed` INT(11) NOT NULL AUTO_INCREMENT,
            `feed_name` VARCHAR(255) NOT NULL,
            `feed_url` VARCHAR(255) NOT NULL,
            PRIMARY KEY (`id_feed`)
        ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8;";

        $sql2 = "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."xmlfeedmanager_product_feed` (
            `id_product` INT(11) NOT NULL,
            `feed_name` VARCHAR(255) NOT NULL,
            PRIMARY KEY (`id_product`, `feed_name`)
        ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8;";

        return Db::getInstance()->execute($sql1) && Db::getInstance()->execute($sql2);
    }

    private function uninstallDb()
    {
        $sql1 = "DROP TABLE IF EXISTS `"._DB_PREFIX_."xmlfeedmanager_feeds`;";
        $sql2 = "DROP TABLE IF EXISTS `"._DB_PREFIX_."xmlfeedmanager_product_feed`;";

        return Db::getInstance()->execute($sql1) && Db::getInstance()->execute($sql2);
    }

    public function getContent()
    {
        $output = null;

        if (Tools::isSubmit('submit' . $this->name)) {
            $feedNames = Tools::getValue('XMLFEEDMANAGER_FEED_NAMES');
            $feedUrls = Tools::getValue('XMLFEEDMANAGER_FEED_URLS');
            Db::getInstance()->execute('TRUNCATE TABLE ' . _DB_PREFIX_ . 'xmlfeedmanager_feeds');
            foreach ($feedNames as $index => $feedName) {
                if (!empty($feedName) && !empty($feedUrls[$index])) {
                    Db::getInstance()->insert('xmlfeedmanager_feeds', array(
                        'feed_name' => pSQL($feedName),
                        'feed_url' => pSQL($feedUrls[$index]),
                    ));
                }
            }
            $output .= $this->displayConfirmation($this->l('Settings updated'));
        }

        return $output . $this->renderForm();
    }

    protected function renderForm()
    {
        $feeds = Db::getInstance()->executeS('SELECT * FROM ' . _DB_PREFIX_ . 'xmlfeedmanager_feeds');

        $feedNames = array();
        $feedUrls = array();

        foreach ($feeds as $feed) {
            $feedNames[] = $feed['feed_name'];
            $feedUrls[] = $feed['feed_url'];
        }

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                ),
                'input' => array(
                    array(
                        'type' => 'hidden',
                        'name' => 'XMLFEEDMANAGER_FEED_NAMES[]',
                    ),
                    array(
                        'type' => 'hidden',
                        'name' => 'XMLFEEDMANAGER_FEED_URLS[]',
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
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->title = $this->displayName;
        $helper->submit_action = 'submit' . $this->name;
        $helper->fields_value = $this->getConfigFieldsValues($feeds);

        return $helper->generateForm(array($fields_form));
    }

    public function getConfigFieldsValues($feeds)
    {
        $feedNames = array();
        $feedUrls = array();

        foreach ($feeds as $feed) {
            $feedNames[] = $feed['feed_name'];
            $feedUrls[] = $feed['feed_url'];
        }

        return array(
            'XMLFEEDMANAGER_FEED_NAMES[]' => $feedNames,
            'XMLFEEDMANAGER_FEED_URLS[]' => $feedUrls,
        );
    }
}
