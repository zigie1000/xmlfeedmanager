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
            `feed_type` ENUM('full', 'update') NOT NULL DEFAULT 'full',
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
            $feedTypes = Tools::getValue('XMLFEEDMANAGER_FEED_TYPES');
            $markup = (float)Tools::getValue('XMLFEEDMANAGER_MARKUP');
            Db::getInstance()->execute('TRUNCATE TABLE ' . _DB_PREFIX_ . 'xmlfeedmanager_feeds');
            foreach ($feedNames as $index => $feedName) {
                if (!empty($feedName) && !empty($feedUrls[$index])) {
                    Db::getInstance()->insert('xmlfeedmanager_feeds', array(
                        'feed_name' => pSQL($feedName),
                        'feed_url' => pSQL($feedUrls[$index]),
                        'feed_type' => pSQL($feedTypes[$index]),
                    ));
                }
            }

            Configuration::updateValue('XMLFEEDMANAGER_MARKUP', $markup);

            $fieldMapping = Tools::getValue('XMLFEEDMANAGER_FIELD_MAPPING');
            Configuration::updateValue('XMLFEEDMANAGER_FIELD_MAPPING', json_encode($fieldMapping));

            $output .= $this->displayConfirmation($this->l('Settings updated'));
        }

        if (Tools::isSubmit('importFeeds')) {
            try {
                $feedHandler = new XmlFeedHandler();
                $feedHandler->importFeeds((float)Configuration::get('XMLFEEDMANAGER_MARKUP'));
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
        $markup = (float)Configuration::get('XMLFEEDMANAGER_MARKUP');

        $feedNames = array();
        $feedUrls = array();
        $feedTypes = array();

        foreach ($feeds as $feed) {
            $feedNames[] = $feed['feed_name'];
            $feedUrls[] = $feed['feed_url'];
            $feedTypes[] = $feed['feed_type'];
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
                        'type' => 'hidden',
                        'name' => 'XMLFEEDMANAGER_FEED_NAMES[]',
                    ),
                    array(
                        'type' => 'hidden',
                        'name' => 'XMLFEEDMANAGER_FEED_URLS[]',
                    ),
                    array(
                        'type' => 'hidden',
                        'name' => 'XMLFEEDMANAGER_FEED_TYPES[]',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Markup Percentage'),
                        'name' => 'XMLFEEDMANAGER_MARKUP',
                        'desc' => $this->l('Enter the markup percentage to be applied to product prices.'),
                        'value' => $markup,
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right'
                )
            )
        );

        // Add feed configurations
        foreach ($feeds as $index => $feed) {
            $fields_form['form']['input'][] = array(
                'type' => 'text',
                'label' => $this->l('Feed Name'),
                'name' => 'XMLFEEDMANAGER_FEED_NAMES[' . $index . ']',
                'value' => $feed['feed_name'],
                'col' => 3
            );
            $fields_form['form']['input'][] = array(
                'type' => 'text',
                'label' => $this->l('Feed URL'),
                'name' => 'XMLFEEDMANAGER_FEED_URLS[' . $index . ']',
                'value' => $feed['feed_url'],
                'col' => 6
            );
            $fields_form['form']['input'][] = array(
                'type' => 'select',
                'label' => $this->l('Feed Type'),
                'name' => 'XMLFEEDMANAGER_FEED_TYPES[' . $index . ']',
                'options' => array(
                    'query' => array(
                        array('id' => 'full', 'name' => $this->l('Full')),
                        array('id' => 'update', 'name' => $this->l('Update'))
                    ),
                    'id' => 'id',
                    'name' => 'name'
                ),
                'value' => $feed['feed_type']
            );
        }

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

        // Add feed history
        $historyHtml = '<div class="panel"><h3>' . $this->l('Feed History') . '</h3><table class="table"><thead><tr><th>' . $this->l('Feed Name') . '</th><th>' . $this->l('URL') . '</th><th>' . $this->l('Type') . '</th><th>' . $this->l('Last Imported') . '</th></tr></thead><tbody>';
        foreach ($feeds as $feed) {
            $historyHtml  .= '<tr><td>' . $feed['feed_name'] . '</td><td>' . $feed['feed_url'] . '</td><td>' . ucfirst($feed['feed_type']) . '</td><td>' . $this->getLastImportDate($feed['feed_name']) . '</td></tr>';
        }
        $historyHtml .= '</tbody></table></div>';

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->title = $this->displayName;
        $helper->submit_action = 'submit' . $this->name;
        $helper->fields_value = $this->getConfigFieldsValues($feeds, $fieldMapping, $markup);

        return $helper->generateForm(array($fields_form)) . $historyHtml;
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
            ['id' => 'quantity', 'name' => $this->l('Quantity')],
            ['id' => 'active', 'name' => $this->l('Active')],
            ['id' => 'weight', 'name' => $this->l('Weight')],
            ['id' => 'width', 'name' => $this->l('Width')],
            ['id' => 'height', 'name' => $this->l('Height')],
            ['id' => 'depth', 'name' => $this->l('Depth')],
            ['id' => 'id_manufacturer', 'name' => $this->l('Manufacturer')],
            ['id' => 'id_supplier', 'name' => $this->l('Supplier')],
        ];
    }

    public function getConfigFieldsValues($feeds, $fieldMapping, $markup)
    {
        $feedNames = array();
        $feedUrls = array();
        $feedTypes = array();

        foreach ($feeds as $feed) {
            $feedNames[] = $feed['feed_name'];
            $feedUrls[] = $feed['feed_url'];
            $feedTypes[] = $feed['feed_type'];
        }

        $fields_values = [
            'XMLFEEDMANAGER_FEED_NAMES' => implode("\n", $feedNames),
            'XMLFEEDMANAGER_FEED_URLS' => implode("\n", $feedUrls),
            'XMLFEEDMANAGER_FEED_TYPES' => implode("\n", $feedTypes),
            'XMLFEEDMANAGER_MARKUP' => $markup,
        ];

        foreach ($fieldMapping as $xmlField => $prestashopField) {
            $fields_values['XMLFEEDMANAGER_FIELD_MAPPING[' . $xmlField . ']'] = $prestashopField;
        }

        return $fields_values;
    }

    protected function getLastImportDate($feedName)
    {
        // Placeholder function to fetch the last import date of the feed
        // This should be implemented to retrieve actual data
        return '2024-08-06';
    }
}
