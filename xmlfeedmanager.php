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

            $fieldMapping = Tools::getValue('XMLFEEDMANAGER_FIELD_MAPPING');
            Configuration::updateValue('XMLFEEDMANAGER_FIELD_MAPPING', json_encode($fieldMapping));

            $output .= $this->displayConfirmation($this->l('Settings updated'));
        }

        if (Tools::isSubmit('importFeeds')) {
            try {
                $feedHandler = new XmlFeedHandler();
                $feedHandler->importFeeds();
                $output .= $this->displayConfirmation($this->l('Feeds imported successfully'));
            } catch (Exception $e) {
                $output .= $this->displayError($this->l('Error importing feeds: ') . $e->getMessage());
            }
        }

        return $output . $this->renderForm();
    }

    protected function renderForm()
    {
        $feeds = Db::getInstance()->executeS('SELECT * FROM ' . _DB_PREFIX_ . 'xmlfeedmanager_feeds');
        $fieldMapping = json_decode(Configuration::get('XMLFEEDMANAGER_FIELD_MAPPING'), true) ?: [];

        $feedNames = array();
        $feedUrls = array();

        foreach ($feeds as $feed) {
            $feedNames[] = $feed['feed_name'];
            $feedUrls[] = $feed['feed_url'];
        }

        $xmlFields = !empty($feeds) ? $this->getXmlFields($feeds[0]['feed_url']) : [];
        $prestashopFields = $this->getPrestashopFields();

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
                        'cols' => 60,
                        'rows' => 10,
                        'desc' => $this->l('Enter the names of the feeds, one per line.'),
                        'value' => implode("\n", $feedNames),
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('Feed URLs (one per line)'),
                        'name' => 'XMLFEEDMANAGER_FEED_URLS',
                        'cols' => 60,
                        'rows' => 10,
                        'desc' => $this->l('Enter the URLs of the feeds, one per line.'),
                        'value' => implode("\n", $feedUrls),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right'
                )
            )
        );

        // Add mapping fields
        foreach ($xmlFields as $xmlField) {
            $fields_form['form']['input'][] = array(
                'type' => 'select',
                'label' => $this->l('Map ' . $xmlField),
                'name' => 'XMLFEEDMANAGER_FIELD_MAPPING[' . $xmlField . ']',
                'options' => array(
                    'query' => $prestashopFields,
                    'id' => 'id',
                    'name' => 'name'
                ),
                'desc' => $this->l('Select the corresponding PrestaShop field for the XML field ') . $xmlField,
                'value' => isset($fieldMapping[$xmlField]) ? $fieldMapping[$xmlField] : ''
            );
        }

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->title = $this->displayName;
        $helper->submit_action = 'submit' . $this->name;
        $helper->fields_value = $this->getConfigFieldsValues($feeds, $fieldMapping);

        return $helper->generateForm(array($fields_form));
    }

    protected function getXmlFields($feedUrl)
    {
        // Fetch the first XML feed to extract field names
        $xmlData = file_get_contents($feedUrl);
        $xml = simplexml_load_string($xmlData);
        $fields = [];

        if ($xml && isset($xml->product[0])) {
            foreach ($xml->product[0] as $key => $value) {
                $fields[] = $key;
            }
        }

        return $fields;
    }

    protected function getPrestashopFields()
    {
        return [
            ['id' => 'name', 'name' => $this->l('Name')],
            ['id' => 'reference', 'name' => $this->l('Reference')],
            ['id' => 'ean13', 'name' => $this->l('EAN13')],
            ['id' => 'upc', 'name' => $this->l('UPC')],
            ['id' => 'price', 'name' => $this->l('Price')],
            ['id' => 'wholesale_price', 'name' => $this->l('Wholesale Price')],
            ['id' => 'description_short', 'name' => $this->l('Short Description')],
            ['id' => 'description', 'name' => $this->l('Description')],
            ['id' => 'id_category_default', 'name' => $this->l('Default Category')],
            ['id' => 'quantity', 'name' => $this->l('Quantity
